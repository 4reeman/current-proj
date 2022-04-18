<?php

namespace Drupal\pulses_exchange_rate;

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
   * Instance of ConfigFactoryInterface.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  private $configFactory;

  /**
   * Instance of CacheBackendInterface.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  private $cache;

  /**
   * Instance of Client class.
   *
   * @param \GuzzleHttp\ClientInterface $http_client
   *   Client object.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   ConfigFactory object.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache
   *   CacheBackendInterface object.
   */
  public function __construct(ClientInterface $http_client, ConfigFactoryInterface $config_factory, CacheBackendInterface $cache) {
    $this->client = $http_client;
    $this->configFactory = $config_factory;
    $this->cache = $cache;
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
    $cid = 'pulses_exchange_rate: nested_data_' . var_export($nested, TRUE);
    if ($cache = $this->cache
      ->get($cid)) {
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
    $cid = 'pulses_exchange_rate: nested_data_' . var_export(TRUE, TRUE);
    $this->cache
      ->set($cid, $refactor, \Drupal::time()->getRequestTime() + (86400));
  }

  /**
   * Create final array (for options of form`s select elements).
   */
  private function setData($data_array) {
    $build = &$this->data;
    foreach ($data_array['data'] as $currency => $value) {
      $build[$currency] = $currency;
    }
    $cid = 'pulses_exchange_rate: nested_data_' . var_export(FALSE, TRUE);
    \Drupal::cache()
      ->set($cid, $build, \Drupal::time()->getRequestTime() + (86400));
  }

}
