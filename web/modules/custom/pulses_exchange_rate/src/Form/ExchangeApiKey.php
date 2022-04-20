<?php

namespace Drupal\pulses_exchange_rate\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form for entering Api key.
 */
class ExchangeApiKey extends ConfigFormBase {

  /**
   * Instance of CurrencyDataProvider.
   *
   * @var \Drupal\pulses_exchange_rate\CurrencyDataProviderInterface
   */
  public $render;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $services = parent::create($container);
    $services->render = $container->get('pulses_exchange_rate_service');
    return $services;
  }

  /**
   * Custom config name.
   *
   * @var string
   */
  const SETTINGS = 'pulses_exchange_rate.settings';

  /**
   * API URl. Prepared to concat with  parameter called api key.
   *
   * @var string
   */
  const API_URL = 'https://api.currencyapi.com/v3/latest?apikey=';

  /**
   * {@inheritDoc}
   */
  public function getFormId() {
    return 'pulses_exchange_rates';
  }

  /**
   * {@inheritDoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * Form for select name of currency.
   *
   * Currency Data Provider provide possibility to check and get data from api
   * for form select elements in 'currency' fieldset wrapper.
   *
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);
    $api_key = $form_state->getValue('api_key');
    $valid_key = preg_match('/^.{40}$/', $api_key);
    $valid_response = FALSE;
    if (!$valid_key) {
      $api_key = $config->get('key');
    }
    else {
      $valid_response = $this->render->getResponse(static::API_URL, $api_key, FALSE);
    }
    $form['api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Please, enter your Api key:'),
      '#default_value' => $config->get('key'),
      '#maxlength' => 40,
      '#description' => $this->t('Press enter for continue filling form'),
      '#ajax' => [
        'callback' => '::validateApiKey',
        'disable-refocus' => TRUE,
        'keypress' => TRUE,
        'event' => 'change',
        'wrapper' => 'currency-wrapper',
        'progress' => FALSE,
      ],
    ];
    $form['currency'] = [
      '#type' => 'container',
      '#attributes' => [
        'id' => 'currency-wrapper',
      ],
    ];
    if (!empty($api_key) && $valid_response) {
      $form['currency']['first'] = [
        '#type' => 'select',
        '#title' => $this->t('Select needed currency:'),
        '#options' => $this->render->data,
        '#default_value' => $config->get('currency.first'),
      ];
      $form['currency']['second'] = [
        '#type' => 'select',
        '#title' => $this->t('Select needed currency:'),
        '#options' => $this->render->data,
        '#default_value' => $config->get('currency.second'),
      ];
      $form['currency']['third'] = [
        '#type' => 'select',
        '#title' => $this->t('Select needed currency:'),
        '#options' => $this->render->data,
        '#default_value' => $config->get('currency.third'),
      ];
      $form['currency']['actions']['#type'] = 'actions';
      $form['currency']['actions']['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Save configuration'),
        '#button_type' => 'primary',
      ];
    }
    // By default, render the form using system-config-form.html.twig.
    $form['#theme'] = 'system_config_form';
    return $form;
  }

  /**
   * Created for calling buildForm function.
   *
   * @param array $form
   *   {@inheritDoc}.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Current state of form.
   */
  public function validateApiKey(array &$form, FormStateInterface $form_state) {
    return $form['currency'];
  }

  /**
   * {@inheritDoc}
   */
  public function validateForm(&$form, FormStateInterface $form_state): void {
    $api_key = $form_state->getValue('api_key');
    $valid_response = TRUE;
    if (strlen($api_key) === 40) {
      $valid_response = $this->render->getResponse(static::API_URL, $api_key, FALSE);
    }
    else {
      $config = $this->config(static::SETTINGS);
      $api_key = $config->get('key');
    }
    if (empty($api_key) || !$valid_response) {
      $form_state->setErrorByName('api_key', $this->t('Invalid key was entered'));
    }
    else {
      $form_state->clearErrors();
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * Get value from form fields and recorde it to config.
   *
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->configFactory->getEditable(static::SETTINGS)
      ->set('key', $form_state->getValue('api_key'))
      ->set('currency.first', $form_state->getValue('first'))
      ->set('currency.second', $form_state->getValue('second'))
      ->set('currency.third', $form_state->getValue('third'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
