<?php

namespace JK\DeployBundle\Module\Modules;

use JK\DeployBundle\Module\AbstractModule;
use JK\DeployBundle\Module\OptionableModuleInterface;
use Symfony\Component\Console\Question\Question;

class NginxModule extends AbstractModule implements OptionableModuleInterface
{
    public function getName(): string
    {
        return 'nginx';
    }

    public function getQuestions(): array
    {
        return [
            'domains' => new Question(
                'Which domain(s) want you to use ? (to use several domains, separate them with a space)',
                'mydomain.com'
            ),
            'user' => new Question(
                'What is the name of the www-user ?',
                'www-data'
            ),
            'group' => new Question(
                'What is the group of the www-user ?',
                'www-data'
            ),
        ];
    }

    public function collect(array $values): array
    {
        return [
            'domains' => explode(' ', $values['domains']),
            'user' => $values['user'],
            'data' => $values['group'],
        ];
    }
}
