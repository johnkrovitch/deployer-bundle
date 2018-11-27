<?php

namespace JK\DeployBundle;

use JK\DeployBundle\DependencyInjection\CompilerPass\ModuleCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class JKDeployBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ModuleCompilerPass());
    }
}
