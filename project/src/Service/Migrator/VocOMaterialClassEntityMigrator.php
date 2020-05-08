<?php


namespace App\Service\Migrator;


class VocOMaterialClassEntityMigrator extends AbstractVocabularyMigrator
{
    protected function generateTableName(): string
    {
        return 'voc__o__material_class';
    }
}