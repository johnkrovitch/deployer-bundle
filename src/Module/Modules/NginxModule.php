<?php

namespace JK\DeployBundle\Module\Modules;

use JK\DeployBundle\Module\AbstractModule;
use JK\DeployBundle\Module\EnvironmentModuleInterface;
use JK\DeployBundle\Module\OptionableModuleInterface;
use JK\DeployBundle\Module\Traits\EnvironmentModuleTrait;
use Symfony\Component\Console\Question\Question;

class NginxModule extends AbstractModule implements OptionableModuleInterface, EnvironmentModuleInterface
{
    use EnvironmentModuleTrait;

    private $vars = [];

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

    public function getTemplates(): array
    {
        return [
            $this->createExtraTemplate(
                'Templates/nginx/virtualhost.conf.twig',
                'templates/nginx/virtualhost.conf.twig',
                [
                    'domains' => $this->vars['domains'],
                    'server_root' => $this->env['hosts.project_path'],
                    'front_controller' => $this->getEnv('nginx.front_controller', 'index.php'),
                    'fast_cgi_path' => $this->getEnv('fast_cgi_path', '/var/run/php/php7.2-fpm.sock'),
                    'client_body_buffer_size' => $this->getEnv('client_body_buffer_size', '5m'),
                    'client_max_body_size' => $this->getEnv('client_max_body_size', '10m'),
                ]
            )
        ];
    }

    public function collect(array $values): array
    {
        $this->vars = [
            'domains' => explode(' ', $values['domains']),
            'user' => $values['user'],
            'group' => $values['group'],
        ];

        return [
            'domains' => explode(' ', $values['domains']),
            'user' => $values['user'],
            'group' => $values['group'],
        ];
    }
}
