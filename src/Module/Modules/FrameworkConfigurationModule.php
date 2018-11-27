<?php

namespace JK\DeployBundle\Module\Modules;

use JK\DeployBundle\Module\AbstractModule;
use JK\DeployBundle\Template\TemplateInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class FrameworkConfigurationModule extends AbstractModule
{
    private $framework = '';

    public function getName(): string
    {
        return 'framework';
    }

    public function getQuestions(): array
    {
        $frameworkQuestion = new ChoiceQuestion(
            'What type of framework do you want to deploy (use symfony for Symfony >= 4.0)',
            [
                'symfony',
                'symfony2'
            ],
            0
        );

        return [
            'framework' => $frameworkQuestion,
        ];
    }

    public function collect(array $values): array
    {
        $this->framework = $values['framework'];

        return [];
    }

    public function getTemplates(): array
    {
        return [
            $this->getDeployTemplate(),
            $this->getExtraTemplate(),
        ];
    }

    private function getDeployTemplate(): ?TemplateInterface
    {
        $mapping = [
            'symfony' => '../../templates/symfony/env.yml.j2',
            'symfony2' => '../../templates/symfony/parameters.yml.j2',
        ];

        return $this->createDeployTemplate(
            'Deploy/framework.configuration.yaml',
            'tasks/deploy/framework.configuration.yaml', [
            'framework.configuration_template' => $mapping[$this->framework],
        ]);
    }

    private function getExtraTemplate(): ?TemplateInterface
    {
        $mapping = [
            'symfony' => 'Templates/symfony.env',
            'symfony2' => 'Templates/symfony.parameters.yaml',
        ];
        $targetMapping = [
            'symfony' => '.env',
            'symfony2' => 'parameters.yml',
        ];

        return $this->createExtraTemplate(
            $mapping[$this->framework],
            'templates/symfony/'.$targetMapping[$this->framework]
        );
    }
}
