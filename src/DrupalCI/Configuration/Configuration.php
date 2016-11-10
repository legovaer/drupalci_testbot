<?php

namespace DrupalCI\Configuration;

class Configuration {

  /**
   * @param $vars
   */
  protected $vars = [];

  /**
   * @return string
   */
  public function __get($property) {
    return array_key_exists($property, $this->vars) ? $this->vars[$property] : "";
  }

  public function __set($property, $value) {
    $this->vars[$property] = $value;
  }

  public function __isset($key) {
    return array_key_exists($key, $this->vars);
  }

  public function override($values) {
    foreach ($values as $key => $value) {
      $this->{$key} = $value;
    }
  }

}