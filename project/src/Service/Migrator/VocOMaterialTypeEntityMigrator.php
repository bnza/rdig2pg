<?php


namespace App\Service\Migrator;


class VocOMaterialTypeEntityMigrator extends AbstractVocabularyMigrator
{
    protected function generateTableName(): string
    {
        return 'voc__o__material_type';
    }
}