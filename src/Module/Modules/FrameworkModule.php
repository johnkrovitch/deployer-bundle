<?php

namespace JK\DeployBundle\Module\Modules;

use JK\DeployBundle\Module\AbstractModule;
use JK\DeployBundle\Module\EnvironmentModuleInterface;
use JK\DeployBundle\Module\Traits\EnvironmentModuleTrait;
use JK\DeployBundle\Template\TemplateInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class FrameworkModule extends AbstractModule implements EnvironmentModuleInterface
{
    use EnvironmentModuleTrait;

    const FRAMEWORK_SYMFONY = 'symfony';
    const FRAMEWORK_SYMFONY_LEGACY = 'symfony_legacy';

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
                self::FRAMEWORK_SYMFONY,
                self::FRAMEWORK_SYMFONY_LEGACY,
            ],
            0
        );

        return [
            'type' => $frameworkQuestion,
        ];
    }

    public function collect(array $values): array
    {
        $this->framework = $values['type'];

        return [];
    }

    public function getTemplates(): array
    {
        $frameworkDeployMapping = [
            self::FRAMEWORK_SYMFONY => 'Deploy/symfony.yaml',
            self::FRAMEWORK_SYMFONY_LEGACY => 'Deploy/symfony.legacy.yaml',
        ];

        return [
            $this->getExtraTemplate(),
            $this->createCopyTemplate(
                $frameworkDeployMapping[$this->framework],
                'tasks/deploy/framework.yaml',
                TemplateInterface::TYPE_DEPLOY
            ),
        ];
    }

    private function getExtraTemplate(): ?TemplateInterface
    {
        $mapping = [
            self::FRAMEWORK_SYMFONY => 'Templates/symfony.env',
            self::FRAMEWORK_SYMFONY_LEGACY => 'Templates/symfony.parameters.yaml',
        ];
        $targetMapping = [
            self::FRAMEWORK_SYMFONY => '.env',
            self::FRAMEWORK_SYMFONY_LEGACY => 'parameters.yml',
        ];

        return $this->createExtraTemplate(
            $mapping[$this->framework],
            'templates/symfony/'.$targetMapping[$this->framework]
        );
    }
}
