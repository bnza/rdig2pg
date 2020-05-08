<?php

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Nelmio\Alice\Loader\NativeLoader;
use Nelmio\Alice\ObjectSet;

trait FixtureLoaderTrait
{
    private NativeLoader $fixturesLoader;

    private function getFixturesLoader(): NativeLoader
    {
        if (!isset($this->fixturesLoader)) {
            $this->fixturesLoader = new NativeLoader();
        }

        return $this->fixturesLoader;
    }

    private function getFixturesPath(string $class): string
    {
        $dirDepth = 1;
        $pathChunks = explode(DIRECTORY_SEPARATOR, __DIR__);
        // Removes App\Tests
        $testClassPathChunks = array_slice(explode('\\', $class), 2);
        array_unshift($testClassPathChunks, 'assets', 'fixtures');
        array_splice($pathChunks, $dirDepth * -1, $dirDepth, $testClassPathChunks);

        return implode(DIRECTORY_SEPARATOR, $pathChunks);
    }

    private function loadFixtures(string $filename = 'fixtures', string $class = self::class): ObjectSet
    {
        $fixturePath = $this->getFixturesPath($class).DIRECTORY_SEPARATOR.$filename.'.yml';

        return $this->getFixturesLoader()->loadFile($fixturePath);
    }

    private function persistFixtures(EntityManagerInterface $em, string $filename = 'fixtures', string $class = self::class): ObjectSet
    {
        $objectSet = $this->loadFixtures($filename, $class);
        foreach ($objectSet->getObjects() as $key => $entity) {
            $em->persist($entity);
        }
        $em->flush();

        return $objectSet;
    }
}
