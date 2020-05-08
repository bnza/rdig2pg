<?php


namespace App\Service\Migrator;


class VocPInclusionsTypeEntityMigrator extends AbstractVocabularyMigrator
{
    protected function generateTableName(): string
    {
        return 'voc__p__inclusions_type';
    }
}