<?php

namespace App\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class FixturesReload
 */
class FixturesReload extends Command
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('doctrine:fixtures:reload')
            // the short description shown while running "php bin/console list"
            ->setDescription('Drop/Create Schema and load Fixtures ....')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to load dummy data by recreating database and loading fixtures...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $application = $this->getApplication();
        $application->setAutoExit(false);

        $this->dropSchema($output, $application);
        $this->createSchema($output, $application);
        $this->loadFixtures($output, $application);
    }

    /**
     * @param OutputInterface $output
     * @param Application $application
     */
    protected function dropSchema(OutputInterface $output, Application $application): void
    {
        $output->writeln([
            '===================================================',
            '*********         Dropping Schema         *********',
            '===================================================',
            '',
        ]);

        $application->run(new ArrayInput(['command' => 'doctrine:schema:drop', "--force" => true]));
    }

    /**
     * @param OutputInterface $output
     * @param Application $application
     */
    protected function createSchema(OutputInterface $output, Application $application): void
    {
        $output->writeln([
            '===================================================',
            '*********         Creating Schema         *********',
            '===================================================',
            '',
        ]);

        $application->run(new ArrayInput(['command' => 'doctrine:schema:update', "--force" => true]));
    }

    /**
     * @param OutputInterface $output
     * @param Application $application
     */
    protected function loadFixtures(OutputInterface $output, Application $application): void
    {
        $output->writeln([
            '===================================================',
            '*********          Load Fixtures          *********',
            '===================================================',
            '',
        ]);

        $application->run(new ArrayInput(['command' => 'doctrine:fixtures:load', "--no-interaction" => true]));
    }
}
