<?php

namespace JK\DeployBundle\Module\Traits;

trait EnvironmentModuleTrait
{
    protected $env;

    public function setEnv(array $env): void
    {
        $this->env = $env;
    }

    public function collect(array $values): array
    {
        return $values;
    }

    public function getEnv(string $parameter, $default = null)
    {
        if (key_exists($parameter, $this->env)) {
            return $this->env[$parameter];
        }

        return $default;
    }
}
