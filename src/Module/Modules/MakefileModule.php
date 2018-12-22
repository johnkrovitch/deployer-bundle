<?php

namespace JK\DeployBundle\Module\Modules;

use JK\DeployBundle\Configuration\ApplicationConfiguration;
use JK\DeployBundle\Module\AbstractModule;
use JK\DeployBundle\Module\EnvironmentModuleInterface;

class MakefileModule extends AbstractModule implements EnvironmentModuleInterface
{
    /**
     * @var array
     */
    private $env;

    /**
     * @var string
     */
    private $prefix;

    public function getName(): string
    {
        return 'makefile';
    }

    public function configure(ApplicationConfiguration $configuration): void
    {
        $this->prefix = $configuration->get('prefix');
    }

    public function getTemplates(): array
    {
        $source = 'Templates/Makefile.twig';

        if (file_exists($this->rootDirectory.'/../../Makefile.dist')) {
            $source = $this->rootDirectory.'/../../Makefile.dist';
        }

        if (file_exists($this->rootDirectory.'/makefile')) {
            $source = $this->rootDirectory.'/makefile';
        }
        $template = $this->createExtraTemplate($source, '../../Makefile', [
            'env' => $this->env['env'],
            'prefix' => $this->prefix,
        ]);
        $template->setAppendToFile(true);

        return [
            $template,
        ];
    }

    public function setEnv(array $env): void
    {
        $this->env = [
            'env' => $env['hosts.env'],
        ];
    }
}
