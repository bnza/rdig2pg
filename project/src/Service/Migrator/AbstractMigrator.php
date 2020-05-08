<?php

namespace App\Service\Migrator;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Statement;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

abstract class AbstractMigrator implements MigratorInterface
{
    private Connection $myConn;
    private Connection $pgConn;
    private EventDispatcherInterface $dispatcher;
    private int $rowsCount;
    private Statement $insertStmt;
    private string $table;
    private string $entityClass;

    protected function generateTableName(): string
    {
        $path = explode('\\', static::class);
        $class = substr(array_pop($path), 0, -14);

        return Inflector::tableize($class);
    }

    public function getTable(): string
    {
        if (!isset($this->table)) {
            $this->table = $this->generateTableName();
        }

        return $this->table;
    }

    abstract public function getInsertQuerySQL(): string;

    public function __construct(EventDispatcherInterface $dispatcher, Connection $myConn, Connection $pgConn)
    {
        $this->dispatcher = $dispatcher;
        $this->myConn = $myConn;
        $this->pgConn = $pgConn;
        $path = explode('\\', static::class);
        $this->entityClass = 'App\\Entity\\'.$class = substr(array_pop($path), 0, -8);
    }

    public function getData(): iterable
    {
        return $this
            ->myConn
            ->prepare(sprintf('SELECT * FROM %s', $this->getTable()))
            ->getIterator();
    }

    public function getRowsCount(): int
    {
        if (!isset($this->rowsCount)) {
            $this->rowsCount = $this->executeCountRows();
        }

        return $this->rowsCount;
    }

    public function migrate(): void
    {
        $this->pgConn->beginTransaction();
        try {
            $insertStmt = $this->getInsertStmt();
            $data = $this->getData();
            $data->execute();
            $this->dispatchStart();
            foreach ($data as $i => $datum) {
                $this->dispatchStep($i);
                $insertStmt->execute($datum);
            }
        } catch (\Exception $e) {
            $this->pgConn->rollBack();
            throw $e;
        }
        $this->pgConn->commit();
        $this->dispatchFinish();
    }

    private function dispatchStart()
    {
        $this->dispatcher->dispatch(
            new GenericEvent(
                MigratorInterface::EVENT_START,
                [
                    'class' => $this->entityClass,
                ]
            ),
            MigratorInterface::EVENT_START
        );
    }

    private function dispatchStep(int $stepNum)
    {
        $this->dispatcher->dispatch(
            new GenericEvent(
                MigratorInterface::EVENT_STEP,
                [
                    'stepNum' => $stepNum,
                    'class' => $this->entityClass,
                ]
            ),
            MigratorInterface::EVENT_STEP
        );
    }

    private function dispatchFinish()
    {
        $this->dispatcher->dispatch(
            new GenericEvent(
                MigratorInterface::EVENT_FINISH,
                [
                    'class' => $this->entityClass,
                ]
            ),
            MigratorInterface::EVENT_FINISH
        );
    }

    private function executeCountRows(): int
    {
        return (int) $this
            ->myConn
            ->query(sprintf('SELECT COUNT(*) FROM %s', $this->getTable()))
            ->fetchColumn();
    }

    private function getInsertStmt(): Statement
    {
        if (!isset($this->insertStmt)) {
            $this->insertStmt = $this->pgConn->prepare($this->getInsertQuerySQL());
        }

        return $this->insertStmt;
    }
}
