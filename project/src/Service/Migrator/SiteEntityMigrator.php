<?php


namespace App\Service\Migrator;


use Doctrine\ORM\Query;

class SiteEntityMigrator extends AbstractMigrator
{
    function getInsertQuerySQL(): string
    {
        $query = 'INSERT INTO %s (id, code, name) VALUES (:id, :code, :name)';
        return sprintf($query, $this->getTable());
    }
}