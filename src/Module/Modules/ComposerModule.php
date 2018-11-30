<?php

namespace JK\DeployBundle\Module\Modules;

use JK\DeployBundle\Module\AbstractModule;
use JK\DeployBundle\Template\TemplateInterface;

class ComposerModule extends AbstractModule
{
    public function getName(): string
    {
        return 'composer';
    }

    public function getTemplates(): array
    {
        return [
            $this->createCopyTemplate('Deploy/composer.yaml', 'tasks/deploy/composer.yaml', TemplateInterface::TYPE_DEPLOY),
            $this->createInstallTemplate('Install/composer.yaml', 'tasks/install/composer.yaml'),
            $this->createRollbackTemplate('Rollback/composer.yaml', 'tasks/rollback/composer.yaml'),
        ];
    }
}
