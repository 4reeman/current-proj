<?php

namespace Drupal\pulses_weather;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use GuzzleHttp\ClientInterface;

/**
 * Object for get data from api.
 */
class PulsesResponseValidation implements PulsesResponseValidationInterface {

  /**
   * Client instance.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  private $client;

  /**
   * Transitional variable.
   *
   * @var \Drupal\pulses_weather\PulsesResponseValidation
   */
  private $header;

  /**
   * LoggerChannelFactory instance.
   *
   * @var \Drupal\Core\Logger\LoggerChannelInterface
   */
  private $logger;

  /**
   * PulsesResponseValidation constructor.
   *
   * @param \GuzzleHttp\ClientInterface $http_client
   *   Client instance.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger
   *   LoggerChannelFactory instance.
   */
  public function __construct(ClientInterface $http_client, LoggerChannelFactoryInterface $logger) {
    $this->client = $http_client;
    $this->logger = $logger->get('pulses_weather');
  }

  /**
   * Check response status.
   *
   * Write Http header to $header.
   *
   * @return bool
   *   Http header status code.
   */
  private function validResponse($url) {
    $request = &$this->header;
    try {
      $request = $this->client->get($url);
    }
    catch (\Exception $e) {
      $this->logger->error($e->getMessage());
      return FALSE;
    }
    return TRUE;
  }

  /**
   * Decode data if response is valid.
   *
   * @return array
   *   Multidimensional data array.
   */
  public function getDecodedData($url) {
    $decoded_data = [];
    if ($this->validResponse($url)) {
      $request = $this->header;
      $json = $request->getBody()->getContents();
      $decoded_data = json_decode($json, TRUE);
      return $decoded_data;
    }
    return $decoded_data;
  }

}
