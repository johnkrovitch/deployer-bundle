<?php

namespace JK\DeployBundle\Template;

interface TemplateInterface
{
    const TYPE_DEPLOY = 'type.deploy';
    const TYPE_INSTALL = 'type.install';
    const TYPE_ROLLBACK = 'type.rollback';
    const TYPE_EXTRA = 'type.extra';

    const PRIORITY_INITIALIZE = -100;
    const PRIORITY_SOURCE = 0;
    const PRIORITY_APPLICATION = 100;
    const PRIORITY_CUSTOM = 200;
    const PRIORITY_FINALIZE = 300;

    public function getType();

    public function getSource(): string;

    public function getParameters(): array;

    public function getTarget(): string;

    public function appendToFile(): bool;

    public function getPriority(): int;
}
