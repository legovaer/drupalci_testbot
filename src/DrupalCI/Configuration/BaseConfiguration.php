<?php
/**
 * @file
 * Contains \DrupalCI\Variable\BaseConfiguration
 */

namespace DrupalCI\Variable;

use Drupal\Component\Annotation\VariableAnnotation as Variable;
use DrupalCI\Confugration\ConfigurationInterface;

/**
 * Base class for configuration objects.
 */
class BaseConfiguration implements ConfigurationInterface  {

  /**
   * @var
   */
  public $dbuser;

  /**
   * Get all properties in one array.
   *
   * @return array
   */
  public function toArray() {
    $array = [];
    $reflectionClass = new \ReflectionClass($this);
    foreach ($reflectionClass->getProperties() as $property) {
      $array[$property->getName()] = $this->{$property->getName()};
    }

    return $array;
  }

}
