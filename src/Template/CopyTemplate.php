<?php

namespace JK\DeployBundle\Template;

class CopyTemplate implements TemplateInterface
{
    private $source;
    private $target;
    private $type;
    private $priority;

    public function __construct(
        string $source,
        string $target,
        string $type,
        int $priority = TemplateInterface::PRIORITY_APPLICATION
    ) {
        $this->source = $source;
        $this->target = $target;
        $this->type = $type;
        $this->priority = $priority;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getParameters(): array
    {
        return [];
    }

    public function getTarget(): string
    {
        return $this->target;
    }

    public function appendToFile(): bool
    {
        return false;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }
}
