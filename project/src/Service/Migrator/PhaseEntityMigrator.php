<?php


namespace App\Service\Migrator;


class PhaseEntityMigrator extends AbstractMigrator
{
    function getInsertQuerySQL(): string
    {
        $query = 'INSERT INTO %s (id, site, name) VALUES (:id, :site, :name)';
        return sprintf($query, $this->getTable());
    }

}