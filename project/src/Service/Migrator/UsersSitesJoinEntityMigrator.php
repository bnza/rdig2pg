<?php

namespace App\Service\Migrator;

class UsersSitesJoinEntityMigrator extends AbstractMigrator
{
    protected function generateTableName(): string
    {
        return 'users_allowed_sites';
    }

    public function getInsertQuerySQL(): string
    {
        $query = 'INSERT INTO %s (id, site_id, user_uuid) VALUES (:id, :site_id, :user_uuid)';

        return sprintf($query, $this->getTable());
    }
}
