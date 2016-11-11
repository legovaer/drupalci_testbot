<?php
/**
 * @file
 * Contains \DrupalCI\Providers\ConfigurationServiceProvider.
 */

namespace DrupalCI\Providers;

use DrupalCI\Configuration\ConfigurationManager;
use Pimple\Container;

/**
 * Defines a service provider which can be used for managing configuration.
 */
class ConfigurationServiceProvider {

  /**
   * Register our Environment
   *
   * @param Container $container
   */
  public function register(Container $container) {
    $container['configuration'] = function ($container) {
      $environment = new ConfigurationManager();
      $environment->inject($container);
      return $environment;
    };
  }

}