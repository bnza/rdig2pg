<?php

namespace App\Service\Migrator;

class UserEntityMigrator extends AbstractMigrator
{
    protected function generateTableName(): string
    {
        return 'app_users';
    }

    public function getInsertQuerySQL(): string
    {
        $query = 'INSERT INTO %s (uuid, username, password, attempts, roles) VALUES (:uuid, :username, :password, :attempts, :roles)';

        return sprintf($query, $this->getTable());
    }
}
