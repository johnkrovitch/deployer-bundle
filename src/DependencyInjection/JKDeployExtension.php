<?php

namespace JK\DeployBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class JKDeployExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $builder)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($builder, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $builder->setParameter('jk_deploy', $config);
    }

    public function getAlias(): string
    {
        return 'jk_deploy';
    }
}
