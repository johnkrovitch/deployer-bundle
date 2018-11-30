<?php

namespace JK\DeployBundle\Module\Modules;

use JK\DeployBundle\Module\AbstractModule;
use JK\DeployBundle\Template\TemplateInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

class DatabaseModule extends AbstractModule
{
    public function getName(): string
    {
        return 'database';
    }

    public function getQuestions(): array
    {
        $driverQuestion = new ChoiceQuestion('What type of database do you want to use ?', [
            'mysql',
        ], 0);
        $hostQuestion = new Question(
            'What is the address of your database server (usually 127.0.0.1) ?',
            '127.0.0.1'
        );
        $portQuestion = new Question(
            'On which port the database server is called  (usually 3306) ?',
            3306
        );
        $nameQuestion = new Question('What is the name of your database on your server ?', 'test.org');
        $userQuestion = new Question('What is the database user ?', 'test_user');
        $passwordQuestion = new Question('What is the user password ?', 'test_password');
        $passwordQuestion->setHidden(true);

        return [
            'driver' => $driverQuestion,
            'host' => $hostQuestion,
            'port' => $portQuestion,
            'name' => $nameQuestion,
            'user' => $userQuestion,
            'password' => $passwordQuestion,
        ];
    }

    public function collect(array $values): array
    {
        return [
            'driver' => $values['driver'],
            'host' => $values['host'],
            'port' => $values['port'],
            'user' => $values['user'],
            'password' => $values['password'],
            'name' => $values['name'],
        ];
    }

    public function getTemplates(): array
    {
        return [
            // TODO move in sf module
            $this->createCopyTemplate('Deploy/database.yaml', 'tasks/deploy/database.yaml', TemplateInterface::TYPE_DEPLOY),
            $this->createInstallTemplate('Install/database.yaml', 'tasks/install/database.yaml'),
            $this->createRollbackTemplate('Rollback/database.yaml', 'tasks/rollback/database.yaml'),
        ];
    }
}
