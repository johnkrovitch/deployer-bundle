<?php

namespace JK\DeployBundle\Module\Registry;

use JK\DeployBundle\Exception\Exception;
use JK\DeployBundle\Module\ModuleInterface;

interface ModuleRegistryInterface
{
    /**
     * @param ModuleInterface $module
     *
     * @throws Exception
     */
    public function add(ModuleInterface $module): void;

    /**
     * @param string $name
     *
     * @return ModuleInterface
     *
     * @throws Exception
     */
    public function get(string $name): ModuleInterface;

    /**
     * @param string $name
     *
     * @return bool
     *
     * @throws Exception
     */
    public function has(string $name): bool;

    /**
     * @return ModuleInterface[]
     *
     * @throws Exception
     */
    public function all(): array;

    /**
     * @throws Exception
     */
    public function freeze(): void;

    /**
     * @return bool
     */
    public function isFrozen(): bool;
}
