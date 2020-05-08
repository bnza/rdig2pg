<?php

namespace App\Tests\Functional\Command;


use App\Tests\AbstractDatabaseTestCase;
use App\Tests\FixtureLoaderTrait;
use App\Tests\Functional\Service\SimplePgMigratorTest;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class MigrateCommandTest extends AbstractDatabaseTestCase
{
    use FixtureLoaderTrait;

    /**
     * @group wip
     */
    public function testMethodMigrate()
    {
        $objects = $this->persistFixtures($this->getEntityManager(), 'fixtures', SimplePgMigratorTest::class);
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('app:migrate');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
        $output = $commandTester->getDisplay();
        $this->assertEquals(0, $commandTester->getStatusCode());
    }
}
