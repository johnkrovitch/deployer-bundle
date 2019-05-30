<?php

namespace JK\DeployBundle\Module\Modules;

use JK\DeployBundle\Configuration\ApplicationConfiguration;
use JK\DeployBundle\Module\AbstractModule;
use JK\DeployBundle\Module\EnvironmentModuleInterface;
use JK\DeployBundle\Module\Traits\EnvironmentModuleTrait;
use JK\DeployBundle\Template\Twig\TwigTemplate;

class MakefileModule extends AbstractModule implements EnvironmentModuleInterface
{
    use EnvironmentModuleTrait;

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
            'env' => $this->env['hosts.env'],
            'prefix' => $this->prefix,
        ]);

        if ($template instanceof TwigTemplate) {
            $template->setAppendToFile(true);
        }

        return [
            $template,
        ];
    }
}
