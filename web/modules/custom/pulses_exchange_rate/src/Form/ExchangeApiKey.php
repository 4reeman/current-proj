<?php

namespace Drupal\pulses_exchange_rate\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form for entering Api key.
 */
class ExchangeApiKey extends ConfigFormBase {

  /**
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'pulses_exchange_rate.settings';

  /**
   * {@inheritDoc}
   */
  public function getFormId() {
    return 'pulses_exchange_rate';
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
   * Build parent Form with new form field api_key.
   *
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('pulses_exchange_rate.settings');
    $form['api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Please, enter your Api key:'),
      '#default_value' => $config->get('api_key'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * Get value from form field called api_key and recorde to config.
   *
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->configFactory->getEditable(static::SETTINGS)
      ->set('api_key', $form_state->getValue('api_key'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
