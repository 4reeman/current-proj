<?php

namespace Drupal\pulses_exchange_rate;

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
  public $header;

  /**
   * Final array with different nested.
   *
   * @var array
   */
  public $data = [];

  /**
   * Instance of ConfigFactoryInterface.
   *
   * @var \Drupal\pulses_exchange_rate\CurrencyDataProvider
   */
  public $configFactory;

  /**
   * Instance of Client class.
   *
   * @param \GuzzleHttp\ClientInterface $http_client
   *   Client object.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   ConfigFactory object.
   */
  public function __construct(ClientInterface $http_client, ConfigFactoryInterface $config_factory) {
    $this->client = $http_client;
    $this->configFactory = $config_factory;
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
    $header = &$this->header;
    try {
      $header = $this->client->get($url . $api_key);
    }
    catch (\Exception $e) {
      return FALSE;
    }
    if ($header->getStatusCode() == '200') {
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
  public function getResponseBody() {
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
  public function responseBodySize($response_body, $max_size) {
    if ($response_body->getSize() < $max_size) {
      return $response_body->read($max_size);
    }
  }

  /**
   * Create final nested array (for block).
   */
  public function setNestedData($data_array) {
    $refactor = &$this->data;
    $config = $this->configFactory->getEditable(ExchangeApiKey::SETTINGS)->getRawData();
    foreach ($config['currency'] as $value) {
      $config_arr[$value] = $value;
    }
    foreach ($data_array['data'] as $currency => $value) {
      $refactor[$currency] = strval($value['value']);
    }
    $refactor = array_intersect_key($refactor, $config_arr);
  }

  /**
   * Create final array (for options of form`s select elements).
   */
  public function setData($data_array) {
    $build = &$this->data;
    foreach ($data_array['data'] as $currency => $value) {
      $build[$currency] = $currency;
    }
  }

}
