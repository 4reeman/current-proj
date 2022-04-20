<?php

namespace Drupal\pulses_exchange_rate\Plugin\Block;

use Drupal\Core\Block\Annotation\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\pulses_exchange_rate\CurrencyDataProviderInterface;
use Drupal\pulses_exchange_rate\Form\ExchangeApiKey;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Exchange Rates' block.
 *
 * @Block(
 *   id = "exchange_rates",
 *   admin_label = @Translation("Exchange Rates"),
 * )
 */
class PulsesExchangeRate extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Instance of CurrencyDataProvider.
   *
   * @var \Drupal\pulses_exchange_rate\CurrencyDataProvider
   */
  public $client;

  /**
   * Instance of ConfigFactory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  private $configFactory;

  /**
   * Construct PulsesExchangeRate class.
   *
   * Get instance of Client class.
   *
   * @param array $configuration
   *   Current plugin configuration.
   * @param string $plugin_id
   *   Current plugin id.
   * @param mixed $plugin_definition
   *   Current plugin definition.
   * @param \Drupal\pulses_exchange_rate\CurrencyDataProviderInterface $currencyProvider
   *   Instance of currencyProvider class.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   ConfigFactory.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, CurrencyDataProviderInterface $currencyProvider, ConfigFactoryInterface $configFactory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->client = $currencyProvider;
    $this->configFactory = $configFactory;
  }

  /**
   * Injecting dependencies.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   {@inheritDoc}.
   * @param array $configuration
   *   Current plugin configuration.
   * @param string $plugin_id
   *   Current plugin id.
   * @param mixed $plugin_definition
   *   Current plugin definition.
   *
   * @return static
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('pulses_exchange_rate_service'),
      $container->get('config.factory'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $api_key = $this->configFactory->getEditable(ExchangeApiKey::SETTINGS)->get('key');
    if (!empty($api_key)) {
      $this->client->getResponse(ExchangeApiKey::API_URL, $api_key, TRUE);
    }
    return [
      '#theme' => 'bla',
      '#currency' => $this->client->data,
    ];
  }

}
