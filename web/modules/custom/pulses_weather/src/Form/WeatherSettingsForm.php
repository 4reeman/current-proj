<?php

namespace Drupal\pulses_weather\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Set API key & default city.
 */
class WeatherSettingsForm extends ConfigFormBase {

  /**
   * PulsesWeatherProvider instance.
   *
   * @var \Drupal\pulses_weather\PulsesWeatherProviderInterface
   */
  private $weatherInfo;

  /**
   * Custom config name.
   *
   * @var string
   */
  const SETTINGS = 'pulses_weather.settings';

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      self::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'weather_settings_form';
  }

  /**
   * Pulses_weather_service was injected.
   *
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): WeatherSettingsForm {
    $services = parent::create($container);
    $services->weatherInfo = $container->get('pulses_weather_service');
    return $services;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);
    $form['weather']['api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Enter your API Key:'),
      '#description' => $this->t('Field should contain 32 characters and valid api key'),
      '#pattern' => '^.{32}$',
      '#default_value' => $config->get('api_key'),
      '#required' => TRUE,
    ];
    $form['weather']['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Enter the default city name:'),
      '#description' => $this->t('Field shouldn`t contain number'),
      '#required' => TRUE,
      '#pattern' => '[a-zA-Z]{2,30}$',
      '#default_value' => $config->get('city'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $api_key = $form_state->getValue('api_key');
    if (!$api_key) {
      $form_state->setErrorByName('api_key',
        $this->t('API Key is empty'));
    }
    if (!$this->weatherInfo->getWeatherInfo($api_key)) {
      $form_state->setErrorByName('api_key',
        $this->t('API Key is invalid'));
    }
    if (!$form_state->getValue('city')) {
      $form_state->setErrorByName('city',
        $this->t('The default city name is empty'));
    }

    parent::validateForm($form, $form_state);
  }

  /**
   * Save valid value to configuration file.
   *
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->configFactory->getEditable(static::SETTINGS)
      ->set('api_key', $form_state->getValue('api_key'))
      ->set('city', $form_state->getValue('city'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
