<?php

namespace JK\DeployBundle\Module\Modules;

use JK\DeployBundle\Module\AbstractModule;

class GitModule extends AbstractModule
{
    public function getName(): string
    {
        return 'git';
    }

    public function getTemplates(): array
    {
        return [
            $this->createDeployTemplate('Deploy/git.yaml', 'tasks/deploy/git.yaml'),
            $this->createRollbackTemplate('Rollback/git.yaml', 'tasks/rollback/git.yaml'),
        ];
    }
}
