<?php

namespace Drupal\pulses_weather;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Cache\CacheBackendInterface;

/**
 * Provide possibility to get weather information for user.
 */
class PulsesWeatherProvider implements PulsesWeatherProviderInterface {

  /**
   * PulsesResponseValidation instance.
   *
   * @var \Drupal\pulses_weather\PulsesResponseValidationInterface
   */
  protected $response;

  /**
   * PulsesCityProvider instance.
   *
   * @var \Drupal\pulses_weather\PulsesCityProviderInterface
   */
  private $userInfo;

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
   * PulsesWeatherProvider constructor.
   *
   * @param \Drupal\pulses_weather\PulsesResponseValidationInterface $response_data
   *   PulsesResponseValidation instance.
   * @param \Drupal\pulses_weather\PulsesCityProviderInterface $user_data
   *   PulsesCityProvider instance.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache
   *   CacheBackend object.
   * @param \Drupal\Component\Datetime\TimeInterface $date_time
   *   TimeInterface object.
   */
  public function __construct(PulsesResponseValidationInterface $response_data, PulsesCityProviderInterface $user_data, CacheBackendInterface $cache, TimeInterface $date_time) {
    $this->response = $response_data;
    $this->userInfo = $user_data;
    $this->cache = $cache;
    $this->dateTime = $date_time;
  }

  /**
   * {@inheritDoc}
   */
  public function getWeatherInfo($api_key) {
    $user_data = $this->userInfo->getData();
    $city = $user_data['city_name'];
    if ($cache = $this->cache->get($city)) {
      return $cache->data;
    }
    $language = $user_data['langcode'];
    $key_param = '&appid=';
    $url = 'https://api.openweathermap.org/data/2.5/weather?q=' . $city . '&lang=' . $language . $key_param . $api_key;
    $data = $this->response->getDecodedData($url);
    if (!empty($data)) {
      $this->cache
        ->set($city, $data, $this->dateTime->getRequestTime() + (86400));
    }
    return $data;
  }

}
