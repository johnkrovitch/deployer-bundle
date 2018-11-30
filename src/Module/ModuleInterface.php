<?php

namespace JK\DeployBundle\Module;

use JK\DeployBundle\Configuration\ApplicationConfiguration;
use JK\DeployBundle\Exception\Exception;
use JK\DeployBundle\Template\TemplateInterface;

interface ModuleInterface
{
    public function getName(): string;

    /**
     * Configure the module with the global parameters.
     *
     * @param ApplicationConfiguration $configuration
     *
     * @throws Exception An exception can be thrown if some parameters are invalid for the module
     */
    public function configure(ApplicationConfiguration $configuration): void;

    public function getQuestions(): array;

    public function collect(array $values): array;

    /**
     * @return TemplateInterface[]
     */
    public function getTemplates(): array;
}
