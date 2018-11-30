<?php

namespace JK\DeployBundle\Module\Modules;

use JK\DeployBundle\Module\AbstractModule;
use JK\DeployBundle\Template\TemplateInterface;

class DeployModule extends AbstractModule
{
    public function getName(): string
    {
        return 'initialize';
    }

    public function getTemplates(): array
    {
        return [
            $this->createCopyTemplate(
                'Deploy/deploy.init.yaml',
                'tasks/deploy/deploy.init.yaml',
                TemplateInterface::TYPE_DEPLOY,
                TemplateInterface::PRIORITY_INITIALIZE
            ),
            $this->createCopyTemplate(
                'Deploy/deploy.finalize.yaml',
                'tasks/deploy/deploy.finalize.yaml',
                TemplateInterface::TYPE_DEPLOY,
                TemplateInterface::PRIORITY_FINALIZE
            ),
        ];
    }
}
