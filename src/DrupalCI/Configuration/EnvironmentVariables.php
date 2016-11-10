<?php

namespace DrupalCI\Configuration;

use Drupal\Component\Annotation\Variable;
use DrupalCI\Injectable;
use Pimple\Container;

/**
 * Object used for mapping environment variables with local variables.
 */
class EnvironmentVariables implements Injectable {

  /**
   * Style object.
   *
   * @var \DrupalCI\Console\DrupalCIStyle
   */
  protected $io;

  /**
   * The container.
   *
   * We need this to inject into other objects.
   *
   * @var \Pimple\Container
   */
  protected $container;

  /**
   * @Variable(
   *   environment  = "DCI_DBType",
   *   description  = "The database type (mysql, pgsql, etc) for a given build.",
   *   defaultValue = "mysql-5.5"
   * )
   */
  public $db_type;

  /**
   * @Variable(
   *   environment  = "DCI_DBVersion",
   *   description  = "The version of the database that will be used.",
   *   defaultValue = "5.5"
   *   parser       = "parseDBVersion"
   * )
   */
  public $db_version;

  /**
   * @Variable(
   *   environment  = "DCI_DBUser",
   *   description  = "The default database user to be used on the site database",
   *   defaultValue = "drupaltestbot"
   * )
   */
  public $dbuser;

  /**
   * @Variable(
   *   environment  = "DCI_DBPassword",
   *   description  = "The default database password to be used on the site",
   *   defaultValue = "drupaltestbotpw"
   * )
   */
  public $dbpassword;

  /**
   * @Variable(
   *   environment  = "DCI_RunScript",
   *   description  = "The default run script to be executed on the container.",
   *   defaultValue = "/var/www/html/core/scripts/run-tests.sh"
   * )
   */
  public $runscript;

  /**
   * @Variable(
   *   environment  = "DCI_PHPInterpreter",
   *   description  = "The php interpreter to be passed to the Run Script in the
   *   --php argument.",
   *   defaultValue = "/opt/phpenv/shims/php"
   * )
   */
  public $php;

  /**
   * @Variable(
   *   environment  = "DCI_Concurrency",
   *   description  = "The value to pass to the --concurrency argument of the run script.",
   *   defaultValue = "4"
   * )
   */
  public $concurrency;

  /**
   * @Variable(
   *   environment = "DCI_RTTypes",
   *   description = "Types that will be used during the execution of the
   *   runscript."
   * )
   *
   * @todo Review & update
   */
  public $types;

  /**
   * @Variable(
   *   environment = "DCI_RTSqlite",
   *   description = "The sqlite file that will be used during the execution of
   *   the run tests script."
   * )
   *
   * @todo Review & update
   */
  public $sqlite;

  /**
   * @Variable(
   *   environment = "DCI_RTUrl",
   *   description = "The URL that will be used during the execution of the run
   *   tests script."
   * )
   *
   * @todo WARNING: We have been defining this before as:
   * if (isset($_ENV['DCI_RTUrl'])) {
   *   $this->configuration['types'] = $_ENV['DCI_RTUrl'];
   * }
   * Was this on purpose or was this a possible bug?
   */
  public $url;

  /**
   * @Variable(
   *   environment = "DCI_RTColor",
   *   description = "(boolean) Indicates if the output of the run tests script
   *   should be colorized."
   * )
   *
   * @todo Review & update
   */
  public $color;

  /**
   * @Variable(
   *   environment      = "DCI_RTTestGroups",
   *   environmentAlias = {"DCI_TestItem", "DCI_TestGroups"},
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
  public $testgroups;

  /**
   * @Variable(
   *   environment = "DCI_RTDieOnFail",
   *   description = "(boolean) Indicates if the build should stop when one of
   *   tests of the run tests script has failed."
   * )
   */
  public $die_on_fail;

  /**
   * @Variable(
   *   environment = "DCI_RTKeepResults",
   *   description = "(boolean) Indicates whether to keep or to delete the test
   *   results when finished."
   * )
   *
   * @todo review
   */
  public $keep_results;

  /**
   * @Variable(
   *   environment = "DCI_RTKeepResultsTable",
   *   description = "(boolean) Indicates whether to keep or to delete the test
   *   results table when finished."
   * )
   *
   * @todo review
   */
  public $keep_results_table;

  /**
   * @Variable(
   *   environment = "DCI_RTVerbose",
   *   description = "(boolean) Indicates whether to use the verbose output."
   * )
   *
   * @todo review
   */
  public $verbose;

  /**
   * @Variable(
   *   environment  = "DCI_PHPVersion",
   *   description  = "The PHP Version used within the executable
   *   container for this build type.",
   *   defaultValue = "5.5"
   * )
   */
  public $phpversion;

  /**
   * @Variable(
   *   environment      = "DCI_CoreRepository"
   *   description      = "The repository URL of Drupal's core."
   *   parser           = "parseRepository"
   *   environmentAlias = {"DCI_CoreBranch", "DCI_GitCheckoutDepth",
   *   "DCI_GitCommitHash", "DCI_AdditionalRepositories"}
   * )
   */
  public $repositories;

  /**
   * @Variable(
   *   environment = "DCI_Fetch",
   *   description = "Used to specify any files which should be downloaded while
   *   building out the codebase."
   * )
   *
   * @TODO make into a test
   * $_ENV['DCI_Fetch']='https://www.drupal.org/files/issues/2796581-region-136.patch,.;https://www.drupal.org/files/issues/another.patch,.';
   */
  public $files;

  /**
   * @Variable(
   *   environment = "DCI_Patch",
   *   description = "Defines any patches which should be applied while building
   *   out the codebase.",
   * )
   *
   * @TODO make into a test
   * $_ENV['DCI_Patch']='https://www.drupal.org/files/issues/2796581-region-136.patch,.;https://www.drupal.org/files/issues/another.patch,.';
   */
  public $patches;

  /**
   * --- Start unused variables
   */

  /**
   * @Variable(
   *   environment = "DCI_UseLocalCodebase",
   *   description = "Used to define a local codebase to be cloned (instead of
   *   performing a Git checkout)"
   * )
   */
  public $localCodeBase;

  /**
   * -- Stop unusued variables
   */

  /**
   * Parses the core repository URL.
   *
   * @param string $value
   *   The URL of the core repository.
   *
   * @return array
   *   A formatted array containing the value.
   */
  public function parseCoreRepository(array $repositories, $type, $value) {
    switch ($type) {
      case "DCI_CoreRepository":
        $repositories[0]['repo'] = $value;
        break;

      case "DCI_CoreBranch":
        $repositories[0]['branch'] = $value;
        break;

      case "DCI_GitCheckoutDepth":
        $repositories[0]['depth'] = $value;
        break;

      case "DCI_GitCommitHash":
        $repositories[0]['commit_hash'] = $value;
        break;

      case "DCI_AdditionalRepositories":
        $repositories[] = $this->parseAdditionalRepositories($value);
    }

    return $repositories;
  }

  /**
   * Parses additional repositories.
   *
   * @param string $value
   *   Details about the additional repositories.
   *
   * @return array
   *   An array containing the data for the additional repositories.
   *
   * @TODO make a test: $_ENV['DCI_AdditionalRepositories']='git,git://git.drupal.org/project/panels.git,8.x-3.x,modules/panels,1;git,git://git.drupal.org/project/ctools.git,8.x-3.0-alpha27,modules/ctools,1;git,git://git.drupal.org/project/layout_plugin.git,8.x-1.0-alpha23,modules/layout_plugin,1;git,git://git.drupal.org/project/page_manager.git,8.x-1.0-alpha24,modules/page_manager,1';
   */
  protected function parseAdditionalRepositories ($value) {
    $repositories = [];
    // Parse the provided repository string into it's components
    $entries = explode(';', $value);
    foreach ($entries as $entry) {
      if (empty($entry)) { continue; }
      $components = explode(',', $entry);
      // Ensure we have at least 3 components
      if (count($components) < 4) {
        $this->io->writeln("<error>Unable to parse repository information for value <options=bold>$entry</options=bold>.</error>");
        // TODO: Bail out of processing.  For now, we'll just keep going with the next entry.
        continue;
      }
      // Create the build definition entry
      $output = array(
        'protocol' => $components[0],
        'repo' => $components[1],
        'branch' => $components[2],
        'checkout_dir' => $components[3]
      );
      if (!empty($components[4])) {
        $output['depth'] = $components[4];
      }
      $repositories[] = $output;
    }

    return $repositories;
  }
  
  /**
   * Parses the database version.
   *
   * @param string $value
   */
  public function parseDBVersion($value) {
    if (strpos($value,'-')) {
      return explode('-', $value, 2)[1];
    } else {
      return $value;
    }
  }

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

  public function inject(Container $container) {
    $this->io = $container['console.io'];
    $this->container = $container;
  }

}
