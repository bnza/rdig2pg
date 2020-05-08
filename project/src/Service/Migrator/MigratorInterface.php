<?php

namespace App\Service\Migrator;

interface MigratorInterface
{
    public function migrate(): void;
}
