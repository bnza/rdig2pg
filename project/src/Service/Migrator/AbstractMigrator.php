<?php

namespace App\Service\Migrator;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Statement;

abstract class AbstractMigrator implements MigratorInterface
{
    private Connection $myConn;
    private Connection $pgConn;
    private int $rowsCount;
    private Statement $insertStmt;
    private string $table;

    protected function generateTableName(): string
    {
        $path = explode('\\', static::class);
        $class = substr(array_pop($path), 0, -14);

        return Inflector::tableize($class);
    }

    protected function getTable(): string
    {
        if (!isset($this->table)) {
            $this->table = $this->generateTableName();
        }

        return $this->table;
    }

    abstract public function getInsertQuerySQL(): string;

    public function __construct(Connection $myConn, Connection $pgConn)
    {
        $this->myConn = $myConn;
        $this->pgConn = $pgConn;
    }

    public function getData(): iterable
    {
        return $this
            ->myConn
            ->prepare(sprintf('SELECT * FROM %s', $this->getTable()))
            ->getIterator();
    }

    public function countRows(): int
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
            foreach ($data as $datum) {
                $insertStmt->execute($datum);
            }
        } catch (\Exception $e) {
            $this->pgConn->rollBack();
            throw $e;
        }
        $this->pgConn->commit();
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
