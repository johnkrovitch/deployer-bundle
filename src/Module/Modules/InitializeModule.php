<?php

namespace JK\DeployBundle\Module\Modules;

use JK\DeployBundle\Module\AbstractModule;

class InitializeModule extends AbstractModule
{
    public function getName(): string
    {
        return 'initialize';
    }

    public function getTemplates(): array
    {
        return [
            $this->createDeployTemplate('Deploy/init.yaml', 'tasks/deploy/init.yaml'),
        ];
    }
}
