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
class VariableAnnotation extends AnnotationBase {

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
   * Indicates whether the variable should be optional.
   *
   * If the variable is optional, it's default value won't get loaded when
   * DrupalCI is loading the default configuration of the plugin.
   *
   * @var bool
   */
  public $optional;

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
   * {@inheritdoc}
   */
  public function get() {
    return array(
      'environment' => $this->environment,
      'description' => $this->class,
      'parser' => $this->parser,
      'environment_alias' => $this->environmentAlias,
      'optional' => $this->optional
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
