<?php

namespace JK\DeployBundle\Module;

use JK\DeployBundle\Configuration\ApplicationConfiguration;
use JK\DeployBundle\Template\TemplateInterface;

interface ModuleInterface
{
    public function getName(): string;

    public function configure(ApplicationConfiguration $configuration): void;

    public function getQuestions(): array;

    public function collect(array $values): array;

    public function getPriority(): int;

    /**
     * @return TemplateInterface[]
     */
    public function getTemplates(): array;
}
