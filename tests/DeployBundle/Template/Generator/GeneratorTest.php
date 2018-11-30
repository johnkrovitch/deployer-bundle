<?php

namespace JK\DeployBundle\Tests\Template\Generator;

use JK\DeployBundle\Exception\Exception;
use JK\DeployBundle\Template\Generator\TemplateGenerator;
use JK\DeployBundle\Template\TemplateInterface;
use JK\DeployBundle\Template\Twig\TwigTemplate;
use JK\DeployBundle\Tests\TestBase;
use PHPUnit\Framework\MockObject\MockObject;

class GeneratorTest extends TestBase
{
    private $cacheDir = __DIR__.'/../../../../var/cache/tests/';

    public function testGenerate()
    {
        list($generator, $twig) = $this->createGenerator();

        $template = $this->createMock(TwigTemplate::class);
        $template
            ->expects($this->atLeastOnce())
            ->method('getTarget')
            ->willReturn('myLittleFile.txt')
        ;
        $template
            ->expects($this->atLeastOnce())
            ->method('getSource')
            ->willReturn('Templates/test.yaml.twig')
        ;
        $template
            ->expects($this->atLeastOnce())
            ->method('getParameters')
            ->willReturn([
                'anOtherUselessParameter' => 'yes',
            ])
        ;

        $twig
            ->expects($this->once())
            ->method('render')
            ->with('Templates/test.yaml.twig', [
                'anOtherUselessParameter' => 'yes',
            ])
            ->willReturn('MyLittleContent')
        ;

        $generator->generate($template);

        $this->assertFileExists($this->cacheDir.'myLittleFile.txt');
        $this->assertEquals('MyLittleContent', file_get_contents($this->cacheDir.'myLittleFile.txt'));
        unlink($this->cacheDir.'myLittleFile.txt');
    }

    public function testGenerateWithAppend()
    {
        list($generator, $twig) = $this->createGenerator();

        $template = $this->createMock(TwigTemplate::class);
        $template
            ->expects($this->atLeastOnce())
            ->method('getTarget')
            ->willReturn('myLittleFile.txt')
        ;
        $template
            ->expects($this->atLeastOnce())
            ->method('getSource')
            ->willReturn('Templates/test.yaml.twig')
        ;
        $template
            ->expects($this->atLeastOnce())
            ->method('getParameters')
            ->willReturn([
                'anOtherUselessParameter' => 'yes',
            ])
        ;
        $template
            ->expects($this->atLeastOnce())
            ->method('appendToFile')
            ->willReturn(true)
        ;

        $twig
            ->expects($this->atLeastOnce())
            ->method('render')
            ->with('Templates/test.yaml.twig', [
                'anOtherUselessParameter' => 'yes',
            ])
            ->willReturn('MyLittleContent')
        ;

        $generator->generate($template);

        $this->assertFileExists($this->cacheDir.'myLittleFile.txt');
        $this->assertEquals(PHP_EOL.'MyLittleContent', file_get_contents($this->cacheDir.'myLittleFile.txt'));

    }

    public function testGenerateWithInvalidTemplate()
    {
        list($generator) = $this->createGenerator();

        $template = $this->createMock(TemplateInterface::class);

        $this->assertExceptionRaised(Exception::class, function () use ($generator, $template) {
            $generator->generate($template);
        });
    }

    protected function tearDown()
    {
        if (file_exists($this->cacheDir.'myLittleFile.txt')) {
            unlink($this->cacheDir.'myLittleFile.txt');
        }
    }

    /**
     * @return TemplateGenerator[]|MockObject[]
     */
    private function createGenerator(): array
    {
        $twig = $this->createMock(\Twig_Environment::class);
        $generator = new TemplateGenerator($this->cacheDir, $twig);

        return [
            $generator,
            $twig,
        ];
    }
}
