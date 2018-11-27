<?php

namespace JK\DeployBundle\Module\Modules;

use JK\DeployBundle\Module\AbstractModule;
use Symfony\Component\Console\Question\Question;

class HostModule extends AbstractModule
{
    private $env = '';

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
        ];
    }

    public function collect(array $values): array
    {
        $this->env = $values['env'];

        return [
            'env' => $this->env,
        ];
    }

    public function getTemplates(): array
    {
        return [
            $this->createExtraTemplate('Extra/host.yaml', 'hosts/'.$this->env.'.yaml'),
        ];
    }
}
