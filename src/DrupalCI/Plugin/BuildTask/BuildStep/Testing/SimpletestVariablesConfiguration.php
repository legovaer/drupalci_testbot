<?php
/**
 * @file
 * Contains \DrupalCI\Plugin\BuildTask\BuildStep\Testing\SimpletestConfiguration.
 */

namespace DrupalCI\Plugin\BuildTask\BuildStep\Testing;

use DrupalCI\Configuration\DatabaseConfigurationTrait;
use DrupalCI\Variable\BaseConfiguration;

/**
 * Defines a configuration class for the Simpletest plugin.
 */
class SimpletestConfiguration extends BaseConfiguration {

  use DatabaseConfigurationTrait;

  /**
   * @Variable(
   *   environment  = "DCI_RunScript",
   *   description  = "The default run script to be executed on the container.",
   *   optional     = 0
   * )
   */
  public $runscript = '/var/www/html/core/scripts/run-tests.sh ';

  /**
   * @Variable(
   *   environment      = "DCI_RTTestGroups",
   *   environmentAlias = {"DCI_TestItem", "DCI_TestGroups"},
   *   optional         = 0
   *   parser           = "parseTestGroups"
   *   description      = "Defines what tests will be executed. There are
   *   various possibilities here: \n
   *     - all                   Will execute all available testcases\n
   *     - module:<module_name>  Will execute all testcases of the given module
   *     \n
   *     - class:<class_name>    Will execute all testcases of the given class\n
   *     - file:<class_name>     Will execute all testscases that are found in
   *     the given filename\n
   *     - directory:<directory> Will execute all testcases that are found in
   *     the given directory."
   * )
   */
  public $testgroups = '-all';

  /**
   * @Variable(
   *   environment  = "DCI_RTSqlite"
   *   description  = "The sqlite file that will be used during the execution of
   *   the run tests script."
   *   optional     = 0
   * )
   */
  public $sqlite = '/var/www/html/artifacts/simpletest.sqlite';

  /**
   * @Variable(
   *   environment  = "DCI_Concurrency",
   *   description  = "The value to pass to the --concurrency argument of the run script.",
   *   optional     = 0
   * )
   */
  public $concurrency = 4;

  /**
   * @Variable(
   *   environment  = "DCI_RTTypes",
   *   description  = "Types that will be used during the execution of the
   *   runscript."
   *   optional     = 0
   * )
   */
  public $types = 'Simpletest,PHPUnit-Unit,PHPUnit-Kernel,PHPUnit-Functional';

  /**
   * @Variable(
   *   environment  = "DCI_RTUrl",
   *   description  = "The URL that will be used during the execution of the run
   *   tests script."
   *   optional     = 0
   * )
   */
  public $url = 'http://localhost/checkout';

  /**
   * @Variable(
   *   environment  = "DCI_PHPInterpreter",
   *   description  = "The php interpreter to be passed to the Run Script in the
   *   --php argument.",
   *   optional     = 0
   * )
   */
  public $php = '/opt/phpenv/shims/php';

  /**
   * @Variable(
   *   environment  = "DCI_RTColor",
   *   description  = "(boolean) Indicates if the output of the run tests script
   *   should be colorized.",
   *   optional     = 0
   * )
   */
  public $color = TRUE;

  /**
   * @Variable(
   *   environment  = "DCI_RTDieOnFail",
   *   description  = "(boolean) Indicates if the build should stop when one of
   *   tests of the run tests script has failed."
   *   optional     = 0
   * )
   */
  public $die_on_fail = FALSE;

  /**
   * @Variable(
   *   environment  = "DCI_RTKeepResults",
   *   description  = "(boolean) Indicates whether to keep or to delete the test
   *   results when finished."
   *   optional     = 0
   * )
   */
  public $keep_results = TRUE;

  /**
   * @Variable(
   *   environment  = "DCI_RTKeepResultsTable",
   *   description  = "(boolean) Indicates whether to keep or to delete the test
   *   results table when finished."
   *   optional     = 0
   * )
   */
  public $keep_results_table = FALSE;

  /**
   * @Variable(
   *   environment  = "DCI_RTVerbose",
   *   description  = "(boolean) Indicates whether to use the verbose output."
   *   optional     = 0
   * )
   */
  public $verbose = FALSE;

  /**
   * Parses the test groups.
   *
   * @param mixed $groups
   */
  public function parseTestGroups($groups) {
    // Special case for 'all'
    if (strtolower($groups) === 'all') {
      return "--all";
    }

    // Split the string components
    $components = explode(':', $groups);
    if (!in_array($components[0], array('module', 'class', 'file', 'directory'))) {
      // Invalid entry.
      return $groups;
    }

    $testgroups = "--" . $components[0] . " " . $components[1];

    return $testgroups;
  }

  /**
   * @return string
   */
  public function getRunscript() {
    return $this->runscript;
  }

  /**
   * @param string $runscript
   */
  public function setRunscript($runscript) {
    $this->runscript = $runscript;
  }

  /**
   * @return string
   */
  public function getTestgroups() {
    return $this->testgroups;
  }

  /**
   * @param string $testgroups
   */
  public function setTestgroups($testgroups) {
    $this->testgroups = $testgroups;
  }

  /**
   * @return string
   */
  public function getSqlite() {
    return $this->sqlite;
  }

  /**
   * @param string $sqlite
   */
  public function setSqlite($sqlite) {
    $this->sqlite = $sqlite;
  }

  /**
   * @return string
   */
  public function getConcurrency() {
    return $this->concurrency;
  }

  /**
   * @param string $concurrency
   */
  public function setConcurrency($concurrency) {
    $this->concurrency = $concurrency;
  }

  /**
   * @return string
   */
  public function getTypes() {
    return $this->types;
  }

  /**
   * @param string $types
   */
  public function setTypes($types) {
    $this->types = $types;
  }

  /**
   * @return string
   */
  public function getUrl() {
    return $this->url;
  }

  /**
   * @param string $url
   */
  public function setUrl($url) {
    $this->url = $url;
  }

  /**
   * @return string
   */
  public function getPhp() {
    return $this->php;
  }

  /**
   * @param string $php
   */
  public function setPhp($php) {
    $this->php = $php;
  }

  /**
   * @return bool
   */
  public function getColor() {
    return $this->color;
  }

  /**
   * @param bool $color
   */
  public function setColor($color) {
    $this->color = $color;
  }

  /**
   * @return bool
   */
  public function getDieOnFail() {
    return $this->die_on_fail;
  }

  /**
   * @param bool $die_on_fail
   */
  public function setDieOnFail($die_on_fail) {
    $this->die_on_fail = $die_on_fail;
  }

  /**
   * @return bool
   */
  public function getKeepResults() {
    return $this->keep_results;
  }

  /**
   * @param bool $keep_results
   */
  public function setKeepResults($keep_results) {
    $this->keep_results = $keep_results;
  }

  /**
   * @return bool
   */
  public function getKeepResultsTable() {
    return $this->keep_results_table;
  }

  /**
   * @param bool $keep_results_table
   */
  public function setKeepResultsTable($keep_results_table) {
    $this->keep_results_table = $keep_results_table;
  }

  /**
   * @return bool
   */
  public function getVerbose() {
    return $this->verbose;
  }

  /**
   * @param bool $verbose
   */
  public function setVerbose($verbose) {
    $this->verbose = $verbose;
  }

}
