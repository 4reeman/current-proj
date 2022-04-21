<?php

namespace Drupal\pulses_weather\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\pulses_weather\Form\WeatherSettingsForm;
use Drupal\pulses_weather\PulsesWeatherProviderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Weather Info' block.
 *
 * @Block(
 *    id = "weather_info",
 *    admin_label = @Translation("Weather Info"),
 * )
 */
class PulsesWeather extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * PulsesWeatherProvider instance.
   *
   * @var \Drupal\pulses_weather\PulsesWeatherProviderInterface
   */
  private $weatherService;

  /**
   * ConfigFactory instance.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  private $configFactory;

  /**
   * PulsesWeather constructor.
   *
   * @param array $configuration
   *   Current plugin configuration.
   * @param string $plugin_id
   *   Current plugin id.
   * @param mixed $plugin_definition
   *   Current plugin definition.
   * @param \Drupal\pulses_weather\PulsesWeatherProviderInterface $pulses_weather
   *   PulsesWeatherProvider instance.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   ConfigFactory instance.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, PulsesWeatherProviderInterface $pulses_weather, ConfigFactoryInterface $config_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->weatherService = $pulses_weather;
    $this->configFactory = $config_factory;
  }

  /**
   * Disable caching for current block.
   *
   * @return int
   *   Set CacheMaxAge.
   */
  public function getCacheMaxAge() {
    return 0;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $key = $this->configFactory
      ->get(WeatherSettingsForm::SETTINGS)
      ->get('api_key');
    return [
      '#theme' => 'weather',
      '#data' => $this->weatherService->getWeatherInfo($key),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('pulses_weather_service'),
      $container->get('config.factory'),
    );
  }

}
