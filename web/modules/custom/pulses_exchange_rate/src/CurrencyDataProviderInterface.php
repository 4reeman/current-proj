<?php

namespace Drupal\pulses_exchange_rate;

/**
 * CurrencyDataProvider Interface for decoding json & getting data.
 */
interface CurrencyDataProviderInterface {

  /**
   * Set data with different nesting.
   *
   * @param string $url
   *   Api Url.
   * @param string $api_key
   *   Api Key.
   * @param bool $nested
   *   Boolean parameter which define array nesting.
   *
   * @return bool
   *   Returns ready-to-use data with different nesting.
   */
  public function getResponse($url, $api_key, $nested);

}
