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
class GenerateMigration extends Command
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('doctrine:database:reset')
            // the short description shown while running "php bin/console list"
            ->setDescription('Reset the database to the most current migration')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to reset the database on the most current migration');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $application = $this->getApplication();
        $application->setAutoExit(false);

        $this->dropDatabase($output, $application);
        $this->createDatabase($output, $application);
        $this->migrate($output, $application);
    }

    /**
     * @param OutputInterface $output
     * @param Application $application
     */
    protected function dropDatabase(OutputInterface $output, Application $application)
    {
        $output->writeln([
            '===================================================',
            '*********        Dropping Database        *********',
            '===================================================',
            '',
        ]);

        $application->run(new ArrayInput(['command' => 'doctrine:database:drop', "--force" => true]));
    }

    /**
     * @param OutputInterface $output
     * @param Application $application
     */
    protected function createDatabase(OutputInterface $output, Application $application): void
    {
        $output->writeln([
            '===================================================',
            '*********        Creating Database        *********',
            '===================================================',
            '',
        ]);
        $application->run(new ArrayInput(['command' => 'doctrine:database:create']));
    }

    /**
     * @param OutputInterface $output
     * @param Application $application
     */
    protected function migrate(OutputInterface $output, Application $application): void
    {
        $output->writeln([
            '===================================================',
            '*********        Migrate Migrations       *********',
            '===================================================',
            '',
        ]);

        $application->run(new ArrayInput([
            'command' => 'doctrine:migrations:migrate',
            '--no-interaction' => true,
        ]));
    }
}
