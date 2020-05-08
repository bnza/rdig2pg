<?php

namespace App\Service\Migrator;

class FindingEntityMigrator extends AbstractMigrator
{
    public function getInsertQuerySQL(): string
    {
        $query = 'INSERT INTO %s (id, bucket, chronology, num, remarks, discr) VALUES (:id, :bucket, :chronology, :num, :remarks, :discr)';

        return sprintf($query, $this->getTable());
    }
}
