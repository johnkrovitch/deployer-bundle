<?php

namespace JK\DeployBundle\Module;

interface EnvironmentModuleInterface
{
    public function setEnv(array $env): void;
}
