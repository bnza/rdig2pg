<?php

namespace App\Command;

use App\Service\Migrator\AbstractMigrator;
use App\Service\SimplePgMigrator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class MigrateCommand extends Command
{
    private EntityManager $myEm;
    private EntityManager $pgEm;
    private EventDispatcherInterface $dispatcher;
    private SimplePgMigrator $migrator;
    private array $sections = [];
    private array $progressBars = [];
    protected static $defaultName = 'app:migrate';

    public function __construct(EventDispatcherInterface $dispatcher, EntityManager $myEm, EntityManager $pgEm)
    {
        parent::__construct();
        $this->dispatcher = $dispatcher;
        $this->myEm = $myEm;
        $this->pgEm = $pgEm;
        $this->migrator = new SimplePgMigrator($this->dispatcher, $this->myEm, $this->pgEm);
    }

    public function onMigratorStart(GenericEvent $event)
    {
        if (array_key_exists($event->getArgument('class'), $this->progressBars)) {
            $this->progressBars[$event->getArgument('class')]->start();
        }
        if (array_key_exists('main', $this->progressBars)) {
            $this->progressBars['main']->advance();
        }
    }

    public function onMigratorStep(GenericEvent $event)
    {
        if (array_key_exists($event->getArgument('class'), $this->progressBars)) {
            $this->progressBars[$event->getArgument('class')]->setProgress($event->getArgument('stepNum'));
        }
    }

    public function onMigratorFinish(GenericEvent $event)
    {
        if (array_key_exists($event->getArgument('class'), $this->progressBars)) {
            $this->progressBars[$event->getArgument('class')]->finish();
        }
    }

    protected function configure()
    {
        $this
            ->setDescription('Migrate rDig from mysql to postgres')
            ->setHelp(
                'This command allows you to migrate rDig data from mysql to postgres'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Migrating rDig from mysql to postgres');

        $this->dispatcher->addListener(AbstractMigrator::EVENT_START, [$this, 'onMigratorStart']);
        $this->dispatcher->addListener(AbstractMigrator::EVENT_STEP, [$this, 'onMigratorStep']);
        $this->dispatcher->addListener(AbstractMigrator::EVENT_FINISH, [$this, 'onMigratorFinish']);

        $this->pgEm->beginTransaction();
        try {
            if ($output instanceof ConsoleOutputInterface) {
                $this->setUpSections($output);
            }
            $this->migrator->migrate();
        } catch (\Exception $e) {
            $this->pgEm->rollback();
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));

            return 1;
        } finally {
            if (array_key_exists('main', $this->progressBars)) {
                $this->progressBars['main']->finish();
            }
        }
        $this->pgEm->commit();
        $io->success('Done');

        return 0;
    }

    private function handleMainProgress(string $command)
    {
    }

    private function setUpSections(ConsoleOutputInterface $output)
    {
        $this->sections['progress'] = $progressSection = $output->section();
        $this->progressBars['main'] = $progressBar = new ProgressBar($progressSection);
        $progressBar->setFormat('<info>%message%</info>: %elapsed:6s%');
        $progressBar->setMessage('Migration started');
        $progressBar->display();
        $progressBar->start();
        foreach ($this->migrator->info() as $class => $info) {
            $this->createSection($output, $class, $info);
        }
    }

    private function createSection(ConsoleOutputInterface $output, string $entityClass, array $info)
    {
        $this->sections[$entityClass] = $section = $output->section();
        $this->progressBars[$entityClass] = $progressBar = new ProgressBar($section, $info['rowsCount']);
        $progressBar->setFormat(sprintf('%\'02d)%30s', count($this->sections) - 1, $info['table']).' %current:5d%/%max:5d% [%bar%] %percent:3s%% %elapsed:6s%');
        $progressBar->display();
    }
}
