<?php

namespace JK\DeployBundle\Module\Registry;

use JK\DeployBundle\Module\ModuleInterface;

interface ModuleRegistryInterface
{
    public function add(ModuleInterface $module): void;

    public function get(string $name): ModuleInterface;

    public function has(string $name): bool;

    /**
     * @return ModuleInterface[]
     */
    public function all(): array;

    public function freeze(): void;

    public function isFrozen(): bool;
}
