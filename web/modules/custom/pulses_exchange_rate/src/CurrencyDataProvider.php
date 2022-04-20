<?php

namespace Drupal\pulses_exchange_rate;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\pulses_exchange_rate\Form\ExchangeApiKey;
use GuzzleHttp\ClientInterface;

/**
 * Object for get data from api.
 */
class CurrencyDataProvider implements CurrencyDataProviderInterface {

  /**
   * Client instance.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  public $client;

  /**
   * Http header.
   *
   * @var object
   */
  private $header;

  /**
   * Final array with different nested.
   *
   * @var array
   */
  public $data = [];

  /**
   * Instance of ConfigFactory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  private $configFactory;

  /**
   * Instance of CacheBackend.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  private $cache;

  /**
   * Instance of Time.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  private $dateTime;

  /**
   * API key. Value set in getResponse.
   *
   * @var \Drupal\pulses_exchange_rate\CurrencyDataProvider
   */
  private $apiKey;

  /**
   * Instance of Client class.
   *
   * @param \GuzzleHttp\ClientInterface $http_client
   *   Client object.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   ConfigFactory object.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache
   *   CacheBackend object.
   * @param \Drupal\Component\Datetime\TimeInterface $date_time
   *   TimeInterface object.
   */
  public function __construct(ClientInterface $http_client, ConfigFactoryInterface $config_factory, CacheBackendInterface $cache, TimeInterface $date_time) {
    $this->client = $http_client;
    $this->configFactory = $config_factory;
    $this->cache = $cache;
    $this->dateTime = $date_time;
  }

  /**
   * Prepare cache ID.
   *
   * @param bool $nested
   *   Describe how to sort array with data after decode.
   *
   * @return string
   *   The cache ID of the data to retrieve.
   */
  private function getCacheId($nested) {
    $api_key = $this->apiKey;
    $nesting = var_export($nested, TRUE);
    return 'pulses_exchange_rate:' . $api_key . ':nested_data_' . $nesting;
  }

  /**
   * Request data by URL & write value to object variable (header).
   *
   * Sort array with data after decode & write value to object variable (data).
   *
   * @param string $url
   *   Url of API page.
   * @param string $api_key
   *   Single parameter for API Url.
   * @param bool $nested
   *   Describe how to sort array with data after decode.
   */
  public function getResponse($url, $api_key, $nested) {
    $this->apiKey = $api_key;
    $cid = $this->getCacheId($nested);
    if ($cache = $this->cache->get($cid)) {
      $this->data = $cache->data;
      return TRUE;
    }
    else {
      $header = &$this->header;
      try {
        $header = $this->client->get($url . $api_key);
      }
      catch (\Exception $e) {
        return FALSE;
      }
      if ($nested) {
        $this->setNestedData($this->getResponseBody());
      }
      else {
        $this->setData($this->getResponseBody());
      }
      return TRUE;
    }
  }

  /**
   * Create multidimensional array from body.
   *
   * Write value to object variable (responseBody).
   */
  private function getResponseBody() {
    $body = $this->header->getBody();
    $string_data = $this->responseBodySize($body, 6000);
    $data = json_decode($string_data, TRUE);
    return $data;
  }

  /**
   * Get string from body of Http message if it`s size does not exceed 6k char.
   *
   * Write value to object variable (responseBody).
   *
   * @param object $response_body
   *   Body of Http message.
   * @param int $max_size
   *   Max size of string which will be created from json data.
   */
  protected function responseBodySize($response_body, $max_size) {
    if ($response_body->getSize() < $max_size) {
      return $response_body->read($max_size);
    }
  }

  /**
   * Create final nested array (for block).
   */
  private function setNestedData($data_array) {
    $refactor = &$this->data;
    $config = $this->configFactory->getEditable(ExchangeApiKey::SETTINGS)->getRawData();
    foreach ($config['currency'] as $value) {
      $config_arr[$value] = $value;
    }
    foreach ($data_array['data'] as $currency => $value) {
      $refactor[$currency] = strval($value['value']);
    }
    $refactor = array_intersect_key($refactor, $config_arr);
    $cid = $this->getCacheId(TRUE);
    $this->cache
      ->set($cid, $refactor, $this->dateTime->getRequestTime() + (86400));
  }

  /**
   * Create final array (for options of form`s select elements).
   */
  private function setData($data_array) {
    $build = &$this->data;
    foreach ($data_array['data'] as $currency => $value) {
      $build[$currency] = $currency;
    }
    $cid = $this->getCacheId(FALSE);
    $this->cache
      ->set($cid, $build, $this->dateTime->getRequestTime() + (86400));
  }

}
