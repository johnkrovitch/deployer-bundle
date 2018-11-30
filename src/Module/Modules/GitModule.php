<?php

namespace JK\DeployBundle\Module\Modules;

use JK\DeployBundle\Module\AbstractModule;
use JK\DeployBundle\Module\EnvironmentModuleInterface;
use JK\DeployBundle\Module\Traits\EnvironmentModuleTrait;
use JK\DeployBundle\Template\TemplateInterface;
use Symfony\Component\Console\Question\Question;

class GitModule extends AbstractModule implements EnvironmentModuleInterface
{
    use EnvironmentModuleTrait;

    public function getName(): string
    {
        return 'git';
    }

    public function getQuestions(): array
    {
        return [
            'repository' => new Question(
                'What is the address of your code repository',
                'git@github.com/MyRepository'
            ),
            'version' => new Question(
                'What is the git reference you want to deploy (it can be a branch, a tag or a commit)',
                'master'
            ),
        ];
    }

    public function getTemplates(): array
    {
        return [
            $this->createCopyTemplate('Deploy/git.yaml', 'tasks/deploy/git.yaml', TemplateInterface::TYPE_DEPLOY, TemplateInterface::PRIORITY_SOURCE),
            $this->createRollbackTemplate('Rollback/git.yaml', 'tasks/rollback/git.yaml'),
        ];
    }
}
