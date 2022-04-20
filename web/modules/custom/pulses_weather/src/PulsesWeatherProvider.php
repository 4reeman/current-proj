<?php

namespace Drupal\pulses_weather;

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
   * PulsesWeatherProvider constructor.
   *
   * @param \Drupal\pulses_weather\PulsesResponseValidationInterface $response_data
   *   PulsesResponseValidation instance.
   * @param \Drupal\pulses_weather\PulsesCityProviderInterface $user_data
   *   PulsesCityProvider instance.
   */
  public function __construct(PulsesResponseValidationInterface $response_data, PulsesCityProviderInterface $user_data) {
    $this->response = $response_data;
    $this->userInfo = $user_data;
  }

  /**
   * {@inheritDoc}
   */
  public function getWeatherInfo($api_key) {
    $user_data = $this->userInfo->getData();
    $city = $user_data['city_name'];
    $language = $user_data['langcode'];
    $key_param = '&appid=';
    $url = 'https://api.openweathermap.org/data/2.5/weather?q=' . $city . '&lang=' . $language . $key_param . $api_key;
    $data = $this->response->getDecodedData($url);
    return $data;
  }

}
