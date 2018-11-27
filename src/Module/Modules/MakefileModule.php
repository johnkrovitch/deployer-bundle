<?php

namespace JK\DeployBundle\Module\Modules;

use JK\DeployBundle\Module\AbstractModule;
use JK\DeployBundle\Template\TemplateInterface;
use JK\DeployBundle\Template\Twig\AppendTemplate;

class MakefileModule extends AbstractModule
{
    public function getName(): string
    {
        return 'makefile';
    }

    public function getExtraTemplate(): ?TemplateInterface
    {
        return null;
        $source = $this->getResourcePath('Template/Makefile');

        if (file_exists($this->rootDirectory.'/../../Makefile.dist')) {
            $source = $this->rootDirectory.'/Makefile';
        }

        if (file_exists($this->rootDirectory.'/makefile')) {
            $source = $this->rootDirectory.'/makefile';
        }

        return new AppendTemplate(
            $source,
            'Makefile'
        );
    }
}
