<?php

namespace JK\DeployBundle\Template;

interface TemplateInterface
{
    public function getType();

    public function getSource(): string;

    public function getParameters(): array;

    public function getTarget(): string;

    public function appendToFile(): bool;
}
