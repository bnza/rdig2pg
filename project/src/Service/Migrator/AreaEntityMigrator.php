<?php


namespace App\Service\Migrator;


class AreaEntityMigrator extends AbstractMigrator
{
    function getInsertQuerySQL(): string
    {
        $query = 'INSERT INTO %s (id, site, code, name, location) VALUES (:id, :site, :code, :name, :location)';
        return sprintf($query, $this->getTable());
    }

}