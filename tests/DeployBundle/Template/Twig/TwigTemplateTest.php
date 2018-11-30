<?php

namespace JK\DeployBundle\Tests\Template\Twig;

use JK\DeployBundle\Template\Twig\TwigTemplate;
use JK\DeployBundle\Tests\TestBase;

class TwigTemplateTest extends TestBase
{
    public function testGetters()
    {
        $template = new TwigTemplate('source', 'target', TwigTemplate::TYPE_ROLLBACK, [
            'thisParameterMatters' => 'a_lot',
        ]);
        $template->setAppendToFile(true);

        $this->assertEquals('source', $template->getSource());
        $this->assertEquals('target', $template->getTarget());
        $this->assertEquals(TwigTemplate::TYPE_ROLLBACK, $template->getType());
        $this->assertEquals([
            'thisParameterMatters' => 'a_lot',
        ], $template->getParameters());
        $this->assertEquals(true, $template->appendToFile());
    }
}
