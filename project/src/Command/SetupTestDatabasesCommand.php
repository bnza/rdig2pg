<?php

namespace App\Command;

use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

class SetupTestDatabasesCommand extends Command
{
    protected static $defaultName = 'app:test:setup-dbs';

    protected function configure()
    {
        $this
            ->setDescription('Set up the test database')
            ->addArgument('dump', InputArgument::OPTIONAL, 'The dump file name without extension', 'latest')
            ->setHelp(
                'This command allows you to set up the test databases dropping the old ones and creating new schemas'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ('test' !== $_ENV['APP_ENV']) {
            throw new RuntimeException('This command should be only run in "test" environment');
        }

        $this->dropSchema($output, 'mysql');
        $this->createSchema($output, 'mysql');

        $this->dropSchema($output, 'postgres');
        $this->createSchema($output, 'postgres');

        return 0;
    }

    private function dropSchema(OutputInterface $output, string $em): int
    {
        $dropCommand = $this->getApplication()->find('doctrine:schema:drop');
        $dropCommandInput = new ArrayInput([
            '--em' => $em,
            '--env' => 'test',
            '--full-database' => true,
            '--quiet' => true,
            'command' => 'doctrine:schema:drop',
            '--force' => true,
        ]);

        $output->writeln("\nDropping schema [$em]");
        return $dropCommand->run($dropCommandInput, new NullOutput);
    }

    private function createSchema(OutputInterface $output, string $em): int
    {
        $createCommand = $this->getApplication()->find('doctrine:schema:create');
        $createCommandInput = new ArrayInput([
            'command' => 'doctrine:schema:create',
            '--em' => $em,
            '--env' => 'test',
        ]);

        $output->writeln("Creating schema [$em]");
        return $createCommand->run($createCommandInput, new NullOutput);
    }
}
