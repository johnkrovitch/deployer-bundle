<?php

namespace JK\DeployBundle\Template\Generator;

use JK\DeployBundle\Template\TemplateInterface;

interface TemplateGeneratorInterface
{
    public function generate(TemplateInterface $template): void;
}
