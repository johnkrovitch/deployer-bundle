<?php

namespace JK\DeployBundle\Module\Registry;

use JK\DeployBundle\Exception\Exception;
use JK\DeployBundle\Module\ModuleInterface;

class ModuleRegistry implements ModuleRegistryInterface
{
    private $registry = [];

    private $frozen = false;

    /**
     * @inheritdoc
     */
    public function add(ModuleInterface $module): void
    {
        if ($this->isFrozen()) {
            throw new Exception('The registry is already frozen');
        }

        if ($this->has($module->getName())) {
            throw new Exception('A module with the name "'.$module->getName().'" is already registered');
        }

        $this->registry[$module->getName()] = $module;
    }

    /**
     * @inheritdoc
     */
    public function get(string $name): ModuleInterface
    {
        if (!$this->isFrozen()) {
            throw new Exception('The registry is not frozen');
        }

        if (!$this->has($name)) {
            throw new Exception('The module "'.$name.'" does not exists');
        }

        return $this->registry[$name];
    }

    /**
     * @inheritdoc
     */
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->registry);
    }

    /**
     * @inheritdoc
     */
    public function all(): array
    {
        if (!$this->isFrozen()) {
            throw new Exception('The registry is not frozen');
        }
        return $this->registry;
    }

    /**
     * @inheritdoc
     */
    public function freeze(): void
    {
        if ($this->frozen) {
            throw new Exception('The registry is already frozen');
        }

        $this->frozen = true;
    }

    /**
     * @inheritdoc
     */
    public function isFrozen(): bool
    {
        return $this->frozen;
    }
}
