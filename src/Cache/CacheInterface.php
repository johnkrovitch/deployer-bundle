<?php

namespace JK\DeployBundle\Cache;

interface CacheInterface
{
    public function set(string $key, $data): void;

    public function get(string $key);

    public function all(): array;

    public function clear(): void;
}
