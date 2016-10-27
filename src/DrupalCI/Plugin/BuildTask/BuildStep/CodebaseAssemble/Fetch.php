<?php

namespace DrupalCI\Plugin\BuildTask\BuildStep\CodebaseAssemble;


use DrupalCI\Build\BuildInterface;
use DrupalCI\Console\Output;
use DrupalCI\Plugin\BuildTask\BuildStep\BuildStepInterface;
use DrupalCI\Plugin\BuildTask\BuildTaskTrait;
use DrupalCI\Plugin\BuildTask\FileHandlerTrait;
use DrupalCI\Plugin\PluginBase;
use DrupalCI\Plugin\BuildTask\BuildTaskInterface;
use GuzzleHttp\Client;

/**
 * @PluginID("fetch")
 */
class Fetch extends PluginBase implements BuildStepInterface, BuildTaskInterface {

  use BuildTaskTrait;
  use FileHandlerTrait;

  /**
   * @inheritDoc
   */
  public function configure() {
    // @TODO make into a test
     // $_ENV['DCI_Fetch']='https://www.drupal.org/files/issues/2796581-region-136.patch,.;https://www.drupal.org/files/issues/another.patch,.';
    if (isset($_ENV['DCI_Fetch'])) {
      $this->configuration['files'] = $this->process($_ENV['DCI_Fetch']);
    }
  }

  /**
   * @inheritDoc
   */
  public function run(BuildInterface $build) {

    $files = $this->configuration['files'];

    if (empty($files)) {
      // OPUT
      Output::writeLn('No files to fetch.');
    }
    foreach ($files as $details) {
      // URL and target directory
      // TODO: Ensure $details contains all required parameters
      if (empty($details['from'])) {
        // OPUT
        Output::error("Fetch error", "No valid target file provided for fetch command.");

        return;
      }
      $url = $details['from'];
      $workingdir = $build->getCodebase()->getWorkingDir();
      $fetchdir = (!empty($details['to'])) ? $details['to'] : $workingdir;
      if (!($directory = $this->validateDirectory($build, $fetchdir))) {
        // Invalid checkout directory
        Output::error("Fetch error", "The fetch directory <info>$directory</info> is invalid.");

        return;
      }
      $info = pathinfo($url);
      try {
        $destination_file = $directory . "/" . $info['basename'];
        $this->httpClient()
          ->get($url, ['save_to' => $destination_file]);
      }
      catch (\Exception $e) {
        // OPUT
        Output::error("Write error", "An error was encountered while attempting to write <info>$url</info> to <info>$destination_file</info>");

        return;
      }
      // OPUT
      Output::writeLn("<comment>Fetch of <options=bold>$url</options=bold> to <options=bold>$destination_file</options=bold> complete.</comment>");
    }
  }

  /**
   * @inheritDoc
   */
  public function complete() {
    // TODO: Implement complete() method.
  }

  /**
   * @inheritDoc
   */
  public function getDefaultConfiguration() {
    return [
      'files' => [],
    ];
  }

  /**
   * @inheritDoc
   */
  public function getChildTasks() {
    // TODO: Implement getChildTasks() method.
  }

  /**
   * @inheritDoc
   */
  public function setChildTasks($buildTasks) {
    // TODO: Implement setChildTasks() method.
  }

  /**
   * @inheritDoc
   */
  public function getShortError() {
    // TODO: Implement getShortError() method.
  }

  /**
   * @inheritDoc
   */
  public function getErrorDetails() {
    // TODO: Implement getErrorDetails() method.
  }

  /**
   * @inheritDoc
   */
  public function getResultCode() {
    // TODO: Implement getResultCode() method.
  }

  /**
   * @inheritDoc
   */
  public function getArtifacts() {
    // TODO: Implement getArtifacts() method.
  }

  /**
   * @return \GuzzleHttp\ClientInterface
   */
  protected function httpClient() {
    if (!isset($this->httpClient)) {
      $this->httpClient = new Client();
    }
    return $this->httpClient;
  }


}