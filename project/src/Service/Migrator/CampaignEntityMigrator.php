<?php


namespace App\Service\Migrator;


class CampaignEntityMigrator extends AbstractMigrator
{
    function getInsertQuerySQL(): string
    {
        $query = 'INSERT INTO %s (id, site, year) VALUES (:id, :site, :year)';
        return sprintf($query, $this->getTable());
    }

}