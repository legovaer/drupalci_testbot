<?php

/**
 * @file
 * Contains \DrupalCI\Configuration\ConfigurationInterface.
 */

namespace DrupalCI\Configration;

/**
 * Defines an common interface for classed configurations.
 */
interface ConfigurationInterface {

  /**
   * Transform object to an array.
   *
   * @return array
   *   An array containing the variable object's properties in one array using
   *   the property's name as the array key and the property's value as the
   *   value of the array element.
   */
  public function toArray();

}