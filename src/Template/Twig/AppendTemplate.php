<?php

namespace JK\DeployBundle\Template\Twig;

use JK\DeployBundle\Template\AppendTemplateInterface;

class AppendTemplate implements AppendTemplateInterface
{
    /**
     * @var string
     */
    private $source;

    /**
     * @var string
     */
    private $target;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var bool
     */
    private $createIfNotExists;

    public function __construct(string $source, string $target, array $parameters = [], $createIfNotExists = true)
    {
        $this->source = $source;
        $this->target = $target;
        $this->parameters = $parameters;
        $this->createIfNotExists = $createIfNotExists;
    }

    public function getSource(): string
    {
        // TODO: Implement getSource() method.
    }

    public function getParameters(): array
    {
        // TODO: Implement getParameters() method.
    }

    public function getTarget(): string
    {
        // TODO: Implement getTarget() method.
    }
}
