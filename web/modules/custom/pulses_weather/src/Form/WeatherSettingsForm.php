<?php

namespace Drupal\pulses_weather\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 *  Form set API key & city by which request for api will send
 */
class WeatherSettingsForm extends ConfigFormBase {

  /**
   * Custom config name.
   *
   * @var string
   */
  const SETTINGS = 'pulses_weather.settings';


  /**
   * @inheritDoc
   */
  protected function getEditableConfigNames() {
    return [
      self::SETTINGS,
    ];
  }

  /**
   * @inheritDoc
   */
  public function getFormId() {
    return 'weather_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['weather']['api_key'] = [
      '#type' => 'textfield',
      '#title' => 'Enter your API Key:',
    ];
    $form['weather']['city'] = [
      '#type' => 'textfield',
      '#title' => 'Enter the default city name:',
    ];
    return parent::buildForm($form, $form_state);
  }

}
