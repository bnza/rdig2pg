<?php


namespace App\Service\Migrator;


class VocPInclusionsSizeEntityMigrator extends AbstractVocabularyMigrator
{
    protected function generateTableName(): string
    {
        return 'voc__p__inclusions_size';
    }
}