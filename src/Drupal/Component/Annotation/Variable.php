<?php

/**
 * @file
 * Contains \Drupal\Component\Annotation\PluginID.
 */

namespace Drupal\Component\Annotation;

/**
 * @Annotation
 *
 * Defines a Plugin annotation object that just contains an ID.
 */
class Variable extends AnnotationBase {

  /**
   * The string that is used for the environment variable.
   *
   * @var string
   */
  public $environment;

  /**
   * A description about the variable.
   *
   * @var string
   */
  public $description;

  /**
   * The parser that needs to be used to parse the environment variable.
   *
   * @var string
   */
  public $parser;

  /**
   * An array containing aliases of environment variables.
   *
   * @var array
   */
  public $environmentAlias;

  /**
   * The default value for a variable.
   *
   * @var string
   */
  public $defaultValue;

  /**
   * @return array
   */
  public function getEnvironmentAlias(): array {
    return $this->environmentAlias;
  }

  /**
   * @param array $environmentAlias
   */
  public function setEnvironmentAlias(array $environmentAlias) {
    $this->environmentAlias = $environmentAlias;
  }

  /**
   * @return string
   */
  public function getDefaultValue(): string {
    return $this->default_value;
  }

  /**
   * @param string $default_value
   */
  public function setDefaultValue(string $default_value) {
    $this->default_value = $default_value;
  }

  /**
   * {@inheritdoc}
   */
  public function get() {
    return array(
      'environment' => $this->environment,
      'description' => $this->class,
      'parser' => $this->parser,
      'environment_alias' => $this->environmentAlias,
      'default_value' => $this->defaultValue
    );
  }

  /**
   * @return string
   */
  public function getEnvironment(): string {
    return $this->environment;
  }

  /**
   * @param string $environment
   */
  public function setEnvironment(string $environment) {
    $this->environment = $environment;
  }

  /**
   * @return string
   */
  public function getDescription(): string {
    return $this->description;
  }

  /**
   * @param string $description
   */
  public function setDescription(string $description) {
    $this->description = $description;
  }

  /**
   * @return mixed
   */
  public function getParser() {
    return $this->parser;
  }

  /**
   * @param mixed $parser
   */
  public function setParser($parser) {
    $this->parser = $parser;
  }

}
