<?php

namespace JK\DeployBundle\DependencyInjection\CompilerPass;

use JK\DeployBundle\Module\Registry\ModuleRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ModuleCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(ModuleRegistry::class)) {
            return;
        }
        $definition = $container->getDefinition(ModuleRegistry::class);
        $taggedServices = $container->findTaggedServiceIds('deploy.module');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('add', [
                new Reference($id),
            ]);
        }
        $definition->addMethodCall('freeze');
    }
}
