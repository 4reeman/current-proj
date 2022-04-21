<?php

namespace Drupal\pulses_weather;

/**
 * PulsesCityProvider Interface for decoding json & getting data.
 */
interface PulsesResponseValidationInterface {

  /**
   * Decode data if response is valid.
   *
   * @param string $url
   *   Resource`s URL.
   *
   * @return array
   *   Multidimensional data array.
   */
  public function getDecodedData($url);

}
