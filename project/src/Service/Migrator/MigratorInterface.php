<?php

namespace App\Service\Migrator;

interface MigratorInterface
{
    const EVENT_START = 'app.migrate.start';
    const EVENT_STEP = 'app.migrate.step';
    const EVENT_FINISH = 'app.migrate.finish';


    public function getTable(): string;

    public function getRowsCount(): int;

    public function migrate(): void;
}
