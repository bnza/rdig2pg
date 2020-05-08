<?php


namespace App\Service\Migrator;


class ContextEntityMigrator extends AbstractMigrator
{
    function getInsertQuerySQL(): string
    {
        $query = <<<EOF
 INSERT INTO %s 
 (id, site, area, phase, chronology, type, c_type, num, description) 
 VALUES 
 (:id, :site, :area, :phase, :chronology, :type, :c_type, :num, :description);
 EOF;
        return sprintf($query, $this->getTable());
    }

}