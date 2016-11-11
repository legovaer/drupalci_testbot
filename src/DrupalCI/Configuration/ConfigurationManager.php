<?php

/**
 * @file
 * Contains \DrupalCI\Configuration\ConfigurationManager.
 */

namespace DrupalCI\Configuration;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\FilesystemCache;
use DrupalCI\Configration\ConfigurationInterface;
use DrupalCI\Injectable;
use Pimple\Container;
use ReflectionClass;
use ReflectionProperty;

/**
 * Defines a configuration manager that can be used for managing configuration
 * classes.
 */
class ConfigurationManager implements Injectable {

  /**
   * The reader used for reading annotations.
   *
   * @var CachedReader
   */
  protected $reader;

  /**
   * The pimple container that stores services.
   *
   * @var Container
   */
  protected $container;

  /**
   * Constructs a ConfigurationManager.
   */
  public function __construct() {
    AnnotationRegistry::registerLoader('class_exists');
    $this->reader = new CachedReader(
      new AnnotationReader(),
      new FilesystemCache(sys_get_temp_dir())
    );
  }

  /**
   * {@inheritdoc}
   */
  public function inject(Container $container) {
    $this->container = $container;
  }

  /**
   * Overrides the variables with the environment variables.
   *
   * This method will check if any environment variables have been set and are
   * related to the given variables object. If there are environment variables
   * set that are mapped to the given variables object, it will override the
   * current values of these variables with the values that are set inside the
   * environment variables.
   *
   * @param ConfigurationInterface $configuration
   *   The variables that need to be overridden.
   *
   * @return ConfigurationInterface
   *   The updated variables object.
   */
  public function overrideWithEnvironmentVariables(ConfigurationInterface &$configuration) {
    $environment_variables = $this->parseEnvironmentVariables();

    foreach ($environment_variables as $environment_variable => $value) {
      $variables->{$this->find($variables, $environment_variable)} = $value;
    }

    return $configuration;
  }

  /**
   * Find a variable by a given environment variable.
   *
   * This method can be used if a variable within the variable object needs to
   * be found if only an environment variable is available.
   *
   * @param ConfigurationInterface $configuration
   *   The object where the variable needs to be searched in.
   * @param string $env_variable
   *   The environment variable that needs to be searched for.
   * @return string|null
   *   The value of the given environment variable or NULL if nothing is found.
   */
  public function find(ConfigurationInterface &$configuration, $env_variable) {
    $environment_variables = $this->getVariablesByEnvironmentVariable($configuration);

    if (array_key_exists($env_variable, $environment_variables)) {
      return $environment_variables[$env_variable];
    }
    else {
      return NULL;
    }

  }

  /**
   * Load all the DCI environment variables.
   */
  private function parseEnvironmentVariables() {
    $environment_variables = [];

    // @todo get rid of $_SERVER as it's used for local development only.
    foreach ([$_ENV, $_SERVER] as $environment_stack) {
      foreach ($environment_stack as $item => $value) {
        if (substr($item, 0, 4) == "DCI_") {
          $environment_variables[$item] = $value;
        }
      }
    }

    return $environment_variables;
  }

  /**
   * @param ConfigurationInterface $configuration
   * @return array
   */
  private function getVariablesByEnvironmentVariable(ConfigurationInterface $configuration) {
    $variables = [];
    $reflectionClass = new ReflectionClass($configuration);

    foreach ($reflectionClass->getProperties() as $property) {
      $reflectionProperty = new ReflectionProperty($reflectionClass->getName(), $property->getName());
      $annotations = $this->reader->getPropertyAnnotations($reflectionProperty)[0];
      $variables[$annotations->environment] = $property->getName();
    }

    return $variables;
  }

  /**
   * Helper method to get a list of all annotations.
   *
   * @param ConfigurationInterface $configuration
   *   The configuration class where the information needs to be extracted from.
   *
   * @return array
   *   An array containing all annotations for the properties within the
   *   configuration class.
   */
  private function getAnnotations(ConfigurationInterface $configuration) {
    $annotation_list = [];
    $reflectionClass = new ReflectionClass($configuration);

    foreach ($reflectionClass->getProperties() as $property) {
      $reflectionProperty = new ReflectionProperty($reflectionClass->getName(), $property->getName());
      $annotations = $this->reader->getPropertyAnnotations($reflectionProperty)[0];
      $annotation_list[$property->getName()] = $annotations;
    }

    return $annotation_list;
  }

  /**
   * Get the default configuration.
   *
   * Gets a list of all variables that are not marked optional.
   *
   * @param ConfigurationInterface $configuration
   *   The configuration class that needs to be checked.
   *
   * @return array
   *   An array containing all configuration variables with their default
   *   values.
   */
  public function getDefaultConfiguration(ConfigurationInterface $configuration) {
    $variables = [];
    $annotations = $this->getAnnotations($configuration);

    foreach ($annotations as $property => $annotation) {
      if ($annotation->optional !== TRUE) {
        $variables[$property] = $configuration->{$property};
      }
    }

    return $variables;
  }

}
