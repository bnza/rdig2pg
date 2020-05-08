<?php


namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class AbstractDatabaseTestCase extends KernelTestCase
{
    private static array $ems = [];

    protected static array $kernelBootOptions = [];

    private static function getTestKernel(): KernelInterface
    {
        if (!isset(self::$kernel)) {
            self::bootKernel(self::$kernelBootOptions);
        }
        return self::$kernel;

    }

    protected function getEntityManager(string $em='mysql'): EntityManagerInterface
    {
        if (!array_key_exists($em, self::$ems)) {
            self::$ems[$em] = self::getTestKernel()
                ->getContainer()
                ->get('doctrine')
                ->getManager($em);
        }
        return self::$ems[$em];
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        foreach (self::$ems as $key => $em) {
            $em->close();
            unset(self::$ems[$key]);
        }
    }

    protected function assertPersistedEntityCount(string $entityClass, int $expected, string $idField = 'id', $em = 'postgres'): void
    {
        $actual = $this
            ->getEntityManager($em)
            ->createQueryBuilder()
            ->from($entityClass, 'e')
            ->select("COUNT(e.$idField)")
            ->getQuery()
            ->getSingleScalarResult();
        $this->assertEquals($expected, $actual, sprintf("Failed asserting that class $entityClass has $expected entities persisted, $actual found"));
    }

    protected function assertMigratedTablesEquals(string $entityClass)
    {
        $actual = $this
            ->getEntityManager('mysql')
            ->createQueryBuilder()
            ->from($entityClass, 'e')
            ->select('e')
            ->getQuery()
            ->getArrayResult();
        $expected = $this
            ->getEntityManager('postgres')
            ->createQueryBuilder()
            ->from($entityClass, 'e')
            ->select('e')
            ->getQuery()
            ->getArrayResult();
        $this->assertEquals($actual, $expected, "Migrates table for $entityClass class does not match");
    }


}