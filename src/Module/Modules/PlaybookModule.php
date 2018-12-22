<?php

namespace JK\DeployBundle\Module\Modules;

use JK\DeployBundle\Module\AbstractModule;
use JK\DeployBundle\Module\LateModuleInterface;
use JK\DeployBundle\Template\Twig\TwigTemplate;

class PlaybookModule extends AbstractModule implements LateModuleInterface
{
    private $tasks = [];

    public function getName(): string
    {
        return 'playbook';
    }

    public function getTemplates(): array
    {
        return [
            $this->createDeployTemplate('Deploy/playbook.yaml.twig', 'playbooks/deploy.yaml', [
                'tasks' => $this->tasks[TwigTemplate::TYPE_DEPLOY],
            ]),
            $this->createInstallTemplate('Install/playbook.yaml', 'playbooks/install.yaml'),
            $this->createRollbackTemplate('Rollback/playbook.yaml', 'playbooks/rollback.yaml'),
        ];
    }

    public function setTasks(array $tasks)
    {
        $this->tasks = $tasks;
    }
}
