<?php

namespace JK\DeployBundle\Module\Modules;

use JK\DeployBundle\Module\AbstractModule;

class AnsibleConfigurationModule extends AbstractModule
{
    public function getName(): string
    {
        return 'ansible_configuration';
    }

    public function getTemplates(): array
    {
        return [
            $this->createDeployTemplate('Environment/ansible.cfg', '../../ansible.cfg'),
            $this->createInstallTemplate('Environment/ansible.cfg', '../../ansible.cfg'),
        ];
    }
}
