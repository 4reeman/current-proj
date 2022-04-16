<?php

namespace Drupal\pulses_weather;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\pulses_exchange_rate\Form\ExchangeApiKey;
use GuzzleHttp\ClientInterface;

class PulsesWeatherProvider {

  /**
   * Client instance.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  public $client;

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

}
