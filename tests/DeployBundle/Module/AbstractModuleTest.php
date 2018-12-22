<?php

namespace JK\DeployBundle\Tests\Module;

use JK\DeployBundle\Configuration\ApplicationConfiguration;
use JK\DeployBundle\Exception\Exception;
use JK\DeployBundle\Tests\Fake\FakeModule;
use JK\DeployBundle\Tests\TestBase;
use Symfony\Component\Filesystem\Filesystem;

class AbstractModuleTest extends TestBase
{
    public function testConfigure()
    {
        $module = new FakeModule();
        $configuration = new ApplicationConfiguration();
        $configuration->set([
            'root_directory' => __DIR__,
        ]);

        $module->configure($configuration);

        $this->assertEquals(__DIR__, $module->getRootDirectory());
        $this->assertInstanceOf(Filesystem::class, $module->getFilesystem());
    }

    public function testConfigureWithoutResolving()
    {
        $module = new FakeModule();
        $configuration = new ApplicationConfiguration();

        $this->assertExceptionRaised(Exception::class, function () use ($module, $configuration) {
            $module->configure($configuration);
        });
    }
}
