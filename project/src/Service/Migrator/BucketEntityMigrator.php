<?php

namespace App\Service\Migrator;

class BucketEntityMigrator extends AbstractMigrator
{
    public function getInsertQuerySQL(): string
    {
        $query = 'INSERT INTO %s (id, campaign, context, type, num) VALUES (:id, :campaign, :context, :type, :num)';

        return sprintf($query, $this->getTable());
    }
}
