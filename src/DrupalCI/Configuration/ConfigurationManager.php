<?php

namespace DrupalCI\Configuration;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Drupal\Component\Annotation\Variable;
use Drupal\Component\Configuration\Exception\EnvironmentVariableNotSetException;

class ConfigurationManager {

  /**
   * A list of all environment variables.
   *
   * @var array
   */
  protected $environmentVariables = [];

  /**
   * A list of defined variables.
   *
   * @var array
   */
  protected $variables;

  /**
   * Constructs a ConfigurationManager.
   */
  public function __construct() {
    AnnotationRegistry::registerLoader('class_exists');
    $this->discoverVariables();
    $this->parseEnvironmentVariables();
  }

  public function test() {
    $this->discoverVariables();
  }

  /**
   * Load all the environment variables.
   */
  private function parseEnvironmentVariables() {
    $environment_stacks = [$_ENV, $_SERVER];
    foreach ($environment_stacks as $environment_stack) {
      foreach ($environment_stack as $item => $value) {
        if (substr($item, 0, 4) == "DCI_") {
          $this->environmentVariables[$item] = $value;
        }
      }
    }
  }

  /**
   * Discovers the list of defined variables.
   */
  protected function discoverVariables() {
    $reflectionClassName = 'DrupalCI\\Configuration\\EnvironmentVariables';
    $reader = new AnnotationReader();
    $reflectionClass = new \ReflectionClass($reflectionClassName);

    foreach ($reflectionClass->getProperties() as $property) {
      $reflectionProperty = new \ReflectionProperty($reflectionClassName, $property->getName());
      $this->variables[$property->getName()] = $reader->getPropertyAnnotations($reflectionProperty)[0];
    }
  }

  /**
   * Loads all available properties.
   */
  public function loadProperties(Configuration &$configuration) {
    foreach ($this->variables as $property => $annotation) {
      /** @var $annotation Variable */

      try {
        $configuration->{$property} = $this->getEnvironmentVariableValue($annotation->getEnvironment());
      }
      catch (EnvironmentVariableNotSetException $e) { }
    }

    return $configuration;
  }

  /**
   * Try to load a given property from the current environment variables.
   *
   * @param string $variable
   *   The variable that needs to be loaded.
   */
  protected function getEnvironmentVariableValue($variable) {
    if (array_key_exists($variable, $this->environmentVariables)) {
      return $this->environmentVariables[$variable];
    }
    else {
      throw new EnvironmentVariableNotSetException();
    }
  }

}
