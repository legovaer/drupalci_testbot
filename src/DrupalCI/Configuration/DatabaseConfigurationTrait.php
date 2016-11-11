<?php
/**
 * @file
 * Contains \DrupalCI\Configuration\DatabaseConfigurationTrait.
 */

namespace DrupalCI\Configuration;

use Drupal\Component\Annotation\VariableAnnotation as Variable;

/**
 * Defines a trait that can be used for database configuration.
 */
trait DatabaseConfigurationTrait {

  /**
   * @Variable(
   *   environment = "DCI_DBUser",
   *   description = "Defines the default database user to be used on the site database.",
   * )
   */
  public $dbuser = 'drupaltestbot';

  /**
   * @Variable(
   *   environment = "DCI_DBUrl",
   *   description = "Define the --dburl parameter to be passed to the run
   *   script. (DBVersion, DBUser and DBPassword variable plugins will populate
   *   this)."
   * )
   */
  public $dburl = 'dbtype://host';

}
