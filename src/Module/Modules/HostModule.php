<?php

namespace JK\DeployBundle\Module\Modules;

use JK\DeployBundle\Module\AbstractModule;
use JK\DeployBundle\Module\EnvironmentModuleInterface;
use JK\DeployBundle\Module\Traits\EnvironmentModuleTrait;
use Symfony\Component\Console\Question\Question;

class HostModule extends AbstractModule implements EnvironmentModuleInterface
{
    use EnvironmentModuleTrait;

    public function getName(): string
    {
        return 'hosts';
    }

    public function getPriority(): int
    {
        return self::PRIORITY_INITIALIZE;
    }

    public function getQuestions(): array
    {
        return [
            'env' => new Question('What is the target environment name (staging, production...)', 'production'),
            'address' => new Question('What is address of your server', '91.23.55.235'),
            'port' => new Question('What is the port of your server', '22'),
            'user' => new Question('What is the user used to log into your server', 'user'),
            'project_path' => new Question('What is the path of your project on your server ?', '/var/www/symfony'),
        ];
    }

    public function getTemplates(): array
    {
        return [
            $this->createExtraTemplate('Extra/host.yaml.twig', 'hosts/'.$this->env['hosts.env'].'.yaml', [
                'hostname' => $this->env['hosts.env'],
            ]),
            $this->createExtraTemplate(
                'Extra/host_vars.yaml.twig',
                'hosts/host_vars/'.$this->env['hosts.env'].'.yaml',
                [
                    'ansible_host' => $this->env['hosts.address'],
                    'ansible_user' => $this->env['hosts.user'],
                    'ansible_port' => $this->env['hosts.port'],
                    'data' => $this->sortEnvData($this->env),
                ]
            ),
        ];
    }

    private function sortEnvData(array $env): array
    {
        $sort = [];

        foreach ($env as $name => $value) {
            $moduleData = explode('.', $name);

            if (!key_exists($moduleData[0], $sort)) {
                $sort[$moduleData[0]] = [];
            }
            $sort[$moduleData[0]][$moduleData[1]] = $value;
        }

        return $sort;
    }
}
