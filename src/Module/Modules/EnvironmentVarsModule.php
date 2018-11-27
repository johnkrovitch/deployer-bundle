<?php

namespace JK\DeployBundle\Module\Modules;

use JK\DeployBundle\Module\AbstractModule;
use JK\DeployBundle\Module\EnvironmentModuleInterface;
use JK\DeployBundle\Template\TemplateInterface;

class EnvironmentVarsModule extends AbstractModule implements EnvironmentModuleInterface
{
    private $env = [];

    public function getName(): string
    {
        return 'environment';
    }

    public function getQuestions(): array
    {
        return [];
    }

    public function collect(array $values): array
    {
        return [];
    }

    public function setEnv(array $env): void
    {
        $this->env = $env;
    }

    public function getTemplates(): array
    {
        return [
            $this->createDeployTemplate('Environment/host_vars.yaml', 'hosts/host_vars/production.yaml', [
                'content' => $this->createContent(),
            ]),
            $this->createInstallTemplate('Environment/host_vars.yaml', 'hosts/host_vars/production.yaml', [
                'content' => $this->createContent(),
            ]),
        ];
    }

    private function createContent(): string
    {
        $content = '';

        foreach ($this->env as $name => $value) {
            $content .= $name.': '.$value.PHP_EOL;
        }

        return $content;
    }
}
