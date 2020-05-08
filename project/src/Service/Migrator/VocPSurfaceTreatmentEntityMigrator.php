<?php


namespace App\Service\Migrator;


class VocPSurfaceTreatmentEntityMigrator extends AbstractVocabularyMigrator
{
    protected function generateTableName(): string
    {
        return 'voc__p__surface_treatment';
    }
}