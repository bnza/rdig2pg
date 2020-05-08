<?php

namespace App\Tests\Functional\Service;

use App\Service\SimplePgMigrator;
use App\Tests\AbstractDatabaseTestCase;
use App\Tests\FixtureLoaderTrait;

class SimplePgMigratorTest extends AbstractDatabaseTestCase
{
    use FixtureLoaderTrait;

    /**
     * @group wip
     */
    public function testMethodMigrate()
    {
        $objects = $this->persistFixtures($this->getEntityManager());
        /**
         * @var SimplePgMigrator $persister
         */
        $persister = self::$kernel->getContainer()->get(SimplePgMigrator::class);
        $persister->migrate();
        foreach (SimplePgMigrator::CLASSES as $class) {
            $this->assertMigratedTablesEquals($class);
        }
    }

}