<?php

namespace App\Service\Migrator;

class SampleEntityMigrator extends AbstractMigrator
{
    public function getInsertQuerySQL(): string
    {
        $query = 'INSERT INTO %s (id, campaign, type, no, status) VALUES (:id, :campaign, :type, :no, :status)';

        return sprintf($query, $this->getTable());
    }
}
