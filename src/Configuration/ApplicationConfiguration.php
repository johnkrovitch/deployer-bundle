<?php

namespace JK\DeployBundle\Configuration;

use JK\Configuration\Configuration;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApplicationConfiguration extends Configuration
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'deploy_tasks' => true,
                'install_tasks' => true,
                'rollback_tasks' => true,
                'extra_tasks' => true,
                'prefix' => 'etc/ansible',
            ])
            ->setRequired([
                'root_directory',
            ])
            ->setAllowedTypes('root_directory', 'string')
            ->setAllowedTypes('deploy_tasks', 'boolean')
            ->setAllowedTypes('install_tasks', 'boolean')
            ->setAllowedTypes('rollback_tasks', 'boolean')
        ;
    }
}
