<?php


namespace App\Service\Migrator;


use Doctrine\Common\Inflector\Inflector;

abstract class AbstractVocabularyMigrator extends AbstractMigrator
{
    protected function generateTableName(): string
    {
        return implode('__',explode('_', parent::generateTableName()));
    }

    function getInsertQuerySQL(): string
    {
        $query = 'INSERT INTO %s (id, value) VALUES (:id, :value)';
        return sprintf($query, $this->getTable());
    }
}