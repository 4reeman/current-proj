<?php

namespace Drupal\pulses_weather;

/**
 * PulsesCityProvider Interface for decoding json & getting data.
 */
interface PulsesCityProviderInterface {

  /**
   * Check of existing record in database by user ID.
   *
   * Preparing data by removing unneeded key before returning.
   * If data does not exist then collect it
   * and fill in pulses pulses_weather_user_location table.
   *
   * @return mixed
   *   Return array with user info (id, langcode).
   */
  public function getData();

}
