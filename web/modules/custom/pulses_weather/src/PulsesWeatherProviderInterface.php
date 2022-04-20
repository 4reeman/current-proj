<?php

namespace Drupal\pulses_weather;

/**
 * PulsesWeatherProviderInterface Interface for decoding json & getting data.
 */
interface PulsesWeatherProviderInterface {

  /**
   * Get weather info using openweathermap API.
   *
   * @param string $api_key
   *   Identification key for openweathermap.
   *
   * @return array
   *   Weather info for current user.
   */
  public function getWeatherInfo($api_key);

}
