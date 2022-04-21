<?php

namespace Drupal\pulses_weather;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\pulses_weather\Form\WeatherSettingsForm;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Provide possibility to get user city & langcode by user ID using ip-api.com.
 */
class PulsesCityProvider implements PulsesCityProviderInterface {

  /**
   * PulsesResponseValidation instance.
   *
   * @var \Drupal\pulses_weather\PulsesResponseValidationInterface
   */
  private $response;

  /**
   * The current database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  private $database;

  /**
   * UserSession instance.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  private $user;

  /**
   * RequestStack instance.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  private $requestStack;

  /**
   * ConfigFactory instance.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  private $configFactory;

  /**
   * PulsesCityProvider constructor.
   *
   * @param \Drupal\pulses_weather\PulsesResponseValidationInterface $response_data
   *   PulsesResponseValidation instance.
   * @param \Drupal\Core\Database\Connection $database
   *   The current database connection.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   UserSession instance.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   RequestStack instance.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   ConfigFactory instance.
   */
  public function __construct(PulsesResponseValidationInterface $response_data, Connection $database, AccountProxyInterface $current_user, RequestStack $request_stack, ConfigFactoryInterface $config_factory) {
    $this->response = $response_data;
    $this->database = $database;
    $this->user = $current_user;
    $this->requestStack = $request_stack;
    $this->configFactory = $config_factory;
  }

  /**
   * Get User IP.
   *
   * @return string|null
   *   User IP.
   */
  private function getUserIp() {
    $request = $this->requestStack;
    $ip = $request->getCurrentRequest()->getClientIp();
    return $ip;
  }

  /**
   * Get city name using ip-api API.
   *
   * @return mixed
   *   City name if response will success.
   */
  protected function getCityName() {
    $userIp = $this->getUserIp();
    $mode = '?fields=16';
    $url = 'http://ip-api.com/json/' . $userIp . $mode;
    $data = $this->response->getDecodedData($url);
    if (empty($data)) {
      return $data;
    }
    return $data['city'];
  }

  /**
   * Check if row exist in pulses_weather_user_location table.
   *
   * @param int $user_id
   *   Id of current user.
   *
   * @return array
   *   Return array with user`s ID, city name,langcode if it exist
   *   or false if not.
   */
  private function dataExist($user_id) {
    $query = $this->database->select('pulses_weather_user_location', 'city')
      ->fields('city', [])
      ->condition('user_id', $user_id)
      ->execute();
    return $query->fetchAssoc();
  }

  /**
   * {@inheritDoc}
   *
   * @throws \Exception
   */
  public function getData() {
    $user = $this->user->id();
    $exist = $this->dataExist($user);
    $default_city = $this->configFactory
      ->get(WeatherSettingsForm::SETTINGS)
      ->get('city');
    $city = $this->getCityName() ?: $default_city;
    $language = $this->user->getPreferredLangcode();
    if (!$exist) {
      $data = [
        'user_id' => $user,
        'city_name' => $city,
        'langcode' => $language,
      ];
      $this->database->insert('pulses_weather_user_location')->fields($data)->execute();
      return $data;
    }
    else {
      unset($exist['id']);
      return $exist;
    }
  }

}
