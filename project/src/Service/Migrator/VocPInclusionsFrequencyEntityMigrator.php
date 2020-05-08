<?php


namespace App\Service\Migrator;


class VocPInclusionsFrequencyEntityMigrator extends AbstractVocabularyMigrator
{
    protected function generateTableName(): string
    {
        return 'voc__p__inclusions_frequency';
    }
}