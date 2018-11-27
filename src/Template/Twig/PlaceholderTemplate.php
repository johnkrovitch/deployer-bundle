<?php

namespace JK\DeployBundle\Template\Twig;

use JK\DeployBundle\Template\TemplateInterface;

class PlaceholderTemplate implements TemplateInterface
{
    const TYPE_DEPLOY = 'type.deploy';
    const TYPE_INSTALL = 'type.install';
    const TYPE_ROLLBACK = 'type.rollback';
    const TYPE_EXTRA = 'type.extra';

    /**
     * @var string
     */
    private $name;

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

    public function __construct(string $name, string $target, string $type, array $parameters = [])
    {
        $this->name = $name;
        $this->target = $target;
        $this->parameters = $parameters;
        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getSource(): string
    {
        return $this->name;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getTarget(): string
    {
        return $this->target;
    }
}
