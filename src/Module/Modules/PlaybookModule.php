<?php

namespace JK\DeployBundle\Module\Modules;

use JK\DeployBundle\Module\AbstractModule;

class PlaybookModule extends AbstractModule
{
    public function getName(): string
    {
        return 'playbook';
    }

    public function getTemplates(): array
    {
        return [
            $this->createDeployTemplate('Deploy/playbook.yaml', 'playbooks/deploy.yaml'),
            $this->createInstallTemplate('Install/playbook.yaml', 'playbooks/install.yaml'),
            $this->createRollbackTemplate('Rollback/playbook.yaml', 'playbooks/rollback.yaml'),
        ];
    }
}
