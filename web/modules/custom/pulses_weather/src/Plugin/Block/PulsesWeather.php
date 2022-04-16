<?php

namespace Drupal\pulses_weather\Plugin\Block;

use Drupal\Core\Block\Annotation\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @Block(
 *    id = "weather_info",
 *    admin_label = @Translation("Weather Info"),
 * )
 */
class WeatherSettingsForm extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @inheritDoc
   */
  public function build() {
    // TODO: Implement build() method.
  }

  /**
   * @inheritDoc
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    // TODO: Implement create() method.
  }

}
