<?php

namespace JK\DeployBundle\Template\Twig;

use JK\DeployBundle\Template\TemplateInterface;

class TwigTemplate implements TemplateInterface
{
    /**
     * @var string
     */
    private $source;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var string
     */
    private $target;

    /**
     * @var string
     */
    private $type;

    private $appendToFile = false;

    /**
     * @var int
     */
    private $priority;

    public function __construct(
        string $source,
        string $target,
        string $type,
        array $parameters = [],
        int $priority = TemplateInterface::PRIORITY_APPLICATION
    ) {
        $this->source = $source;
        $this->target = $target;
        $this->parameters = $parameters;
        $this->type = $type;
        $this->priority = $priority;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getTarget(): string
    {
        return $this->target;
    }

    public function appendToFile(): bool
    {
        return $this->appendToFile;
    }

    public function setAppendToFile(bool $appendToFile): void
    {
        $this->appendToFile = $appendToFile;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }
}
