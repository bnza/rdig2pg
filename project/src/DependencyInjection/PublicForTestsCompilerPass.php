<?php
namespace App\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class PublicForTestsCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $containerBuilder): void
    {
        foreach ($containerBuilder->getDefinitions() as $definition) {
            if (preg_match('/^App\\\\(?!Kernel)/', $definition->getClass())) {
                $definition->setPublic(true);
            }
        }

/*        foreach ($containerBuilder->getAliases() as $definition) {
            if (preg_match('/^App\\\\(?!Kernel)/', $definition->getClass())) {
                $definition->setPublic(true);
            }
        }*/
    }
}