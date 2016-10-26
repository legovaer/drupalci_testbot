<?php
/**
 * @file
 * Contains \DrupalCI\Plugin\BuildSteps\setup\Checkout
 *
 * Processes "setup: checkout:" instructions from within a build definition.
 */

namespace DrupalCI\Plugin\BuildSteps\setup;

use DrupalCI\Console\Output;
use DrupalCI\Build\BuildInterface;

/**
 * @PluginID("checkout")
 */
class Checkout extends SetupBase {

  /**
   * {@inheritdoc}
   */
  public function run(BuildInterface $build, $data) {
    // Data format:
    // i) array('protocol' => 'local', 'srcdir' => '/tmp/drupal', 'checkout_dir' => '/tmp/checkout')
    // checkout_dir is optional.
    // or
    // ii) array('protocol' => 'git', 'repo' => 'git://code.drupal.org/drupal.git', 'branch' => '8.0.x', 'depth' => 1)
    // depth is optional
    // or
    // iii) array(array(...), array(...))
    // Normalize data to the third format, if necessary
    $data = (count($data) == count($data, COUNT_RECURSIVE)) ? [$data] : $data;

    // OPUT
    Output::writeLn("<info>Populating container codebase data volume.</info>");
    foreach ($data as $details ) {
      // TODO: Ensure $details contains all required parameters
      $details += ['protocol' => 'git'];
      switch ($details['protocol']) {
        case 'local':
          $this->setupCheckoutLocal($build, $details);
          break;
        case 'git':
          $this->setupCheckoutGit($build, $details);
          break;
      }
      // Break out of loop if we've encountered any errors
      if ($build->getErrorState() !== FALSE) {
        break;
      }
    }
    return;
  }

  protected function setupCheckoutLocal(BuildInterface $build, $details) {
    $source_dir = isset($details['source_dir']) ? $details['source_dir'] : './';
    $checkout_dir = isset($details['checkout_dir']) ? $details['checkout_dir'] : $build->getCodebase()->getWorkingDir();
    // TODO: Ensure we don't end up with double slashes
    // Validate source directory
    if (!is_dir($source_dir)) {
      // OPUT
      Output::error("Directory error", "The source directory <info>$source_dir</info> does not exist.");
      $build->error();
      return;
    }
    // Validate target directory.  Must be within workingdir.
    if (!($directory = $this->validateDirectory($build, $checkout_dir))) {
      // Invalidate checkout directory
      // OPUT
      Output::error("Directory error", "The checkout directory <info>$directory</info> is invalid.");
      $build->error();
      return;
    }
    // OPUT
    Output::writeln("<comment>Copying files from <options=bold>$source_dir</options=bold> to the local checkout directory <options=bold>$directory</options=bold> ... </comment>");
    // TODO: Make sure target directory is empty
#    $this->exec("cp -r $source_dir/. $directory", $cmdoutput, $result);
    $exclude_var = isset($details['DCI_EXCLUDE']) ? '--exclude="' . $details['DCI_EXCLUDE'] . '"' : "";
    $this->exec("rsync -a $exclude_var  $source_dir/. $directory", $cmdoutput, $result);
    if ($result !== 0) {
      // OPUT
      Output::error("Copy error", "Error encountered while attempting to copy code to the local checkout directory.");
      $build->error();
      return;
    }
    // OPUT
    Output::writeLn("<comment>DONE</comment>");
  }

  protected function setupCheckoutGit(BuildInterface $build, $details) {
    // OPUT
    Output::writeLn("<info>Entering setup_checkout_git().</info>");
    $repo = isset($details['repo']) ? $details['repo'] : 'git://drupalcode.org/project/drupal.git';

    $git_branch = isset($details['branch']) ? $details['branch'] : 'master';
    $checkout_directory = isset($details['checkout_dir']) ? $details['checkout_dir'] : $build->getCodebase()->getWorkingDir();
    // TODO: Ensure we don't end up with double slashes
    // Validate target directory.  Must be within workingdir.
    if (!($directory = $this->validateDirectory($build, $checkout_directory))) {
      // Invalid checkout directory
      // OPUT
      Output::error("Directory Error", "The checkout directory <info>$directory</info> is invalid.");
      $build->error();
      return;
    }
    if (substr($details['repo'],0,4) == 'file') {
      // If the repository is specified as a local file://tmp/project, then we rsync the
      // project over to avoid re-composering and re-cloning.
      $exclude_var = isset($details['DCI_EXCLUDE']) ? '--exclude="' . $details['DCI_EXCLUDE'] . '"' : "";
      $source_dir = substr($details['repo'],7);
      $cmd = "rsync -a $exclude_var  $source_dir/. $directory";
      // OPUT
      Output::writeLn("<comment>Performing rsync of git checkout of $repo $git_branch branch to $directory.</comment>");
      Output::writeLn("Rsync Command: $cmd");
      $this->exec($cmd, $cmdoutput, $result);
      if ($result !== 0) {
        // Git threw an error.
        // OPUT
        Output::error("Checkout Error", "The rsync returned an error.  Error Code: $result");
        $build->error();
        return;
      }
    } else {
      // OPUT
      Output::writeLn("<comment>Performing git checkout of $repo $git_branch branch to $directory.</comment>");
      // TODO: Make sure target directory is empty
      $git_depth = '';
      if (isset($details['depth']) && empty($details['commit_hash'])) {
        $git_depth = '--depth ' . $details['depth'];
      }
      $cmd = "git clone -b $git_branch $git_depth $repo '$directory'";
      // OPUT
      Output::writeLn("Git Command: $cmd");
      $this->exec($cmd, $cmdoutput, $result);

      if ($result !== 0) {
        // Git threw an error.
        // OPUT
        Output::error("Checkout Error", "The git checkout returned an error.  Error Code: $result");
        $build->error();
        return;
      }
    }

    if (!empty($details['commit_hash'])) {
      $cmd =  "cd " . $directory . " && git reset -q --hard " . $details['commit_hash'] . " ";
      // OPUT
      Output::writeLn("Git Command: $cmd");
      $this->exec($cmd, $cmdoutput, $result);
    }
    if ($result !==0) {
      // Git threw an error.
      $build->errorOutput("Checkout failed", "The git checkout returned an error.");
      // TODO: Pass on the actual return value for the git checkout
      return;
    }

    $cmd = "cd '$directory' && git log --oneline -n 1 --decorate";
    $this->exec($cmd, $cmdoutput, $result);
    // OPUT
    Output::writeLn("<comment>Git commit info:</comment>");
    Output::writeLn("<comment>\t" . implode($cmdoutput));

    Output::writeLn("<comment>Checkout complete.</comment>");
  }

}