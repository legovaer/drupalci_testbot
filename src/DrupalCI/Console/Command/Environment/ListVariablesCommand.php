<?php

/*
 * This file is part of the Behat.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DrupalCI\Console\Command\Environment;

use Behat\Behat\Definition\DefinitionWriter;
use Behat\Behat\Definition\Printer\ConsoleDefinitionInformationPrinter;
use Behat\Behat\Definition\Printer\ConsoleDefinitionListPrinter;
use Behat\Behat\Definition\Printer\DefinitionPrinter;
use Behat\Testwork\Cli\Controller;
use Behat\Testwork\Suite\SuiteRepository;
use DrupalCI\Console\Command\Drupal\DrupalCICommandBase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Shows all currently available definitions to the user.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class ListVariablesCommand extends DrupalCICommandBase
{

  /**
   * {@inheritdoc}
   */
  public function configure()
  {
    $this->addOption('--variables', '-EV', InputOption::VALUE_REQUIRED,
      "Print all available environment variables:" . PHP_EOL .
      "- use <info>--variables l</info> to just list environment variables." . PHP_EOL .
      "- use <info>--variables i</info> to show environment variables with extended info." . PHP_EOL .
      "- use <info>--variables 'needle'</info> to find specific environment variables."
    )
      ->setName('variables')
      ->setDescription('Displays all the avaiable environment variables.');

  }

  /**
   * {@inheritdoc}
   */
  public function execute(InputInterface $input, OutputInterface $output)
  {
    if (null === $argument = $input->getOption('variables')) {
      return null;
    }

   $this->io->writeln("Hello");

    return 0;
  }

  /**
   * Returns definition printer for provided option argument.
   *
   * @param string $argument
   *
   * @return DefinitionPrinter
   */
  private function getDefinitionPrinter($argument)
  {
    if ('l' === $argument) {
      return $this->listPrinter;
    }

    if ('i' !== $argument) {
      $this->infoPrinter->setSearchCriterion($argument);
    }

    return $this->infoPrinter;
  }
}
