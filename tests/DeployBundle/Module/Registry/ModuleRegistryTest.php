<?php

namespace JK\DeployBundle\Tests\Module\Registry;

use JK\DeployBundle\Exception\Exception;
use JK\DeployBundle\Module\ModuleInterface;
use JK\DeployBundle\Module\Registry\ModuleRegistry;
use JK\DeployBundle\Tests\TestBase;

class ModuleRegistryTest extends TestBase
{
    public function testServiceExists()
    {
        $this->assertServiceExists(ModuleRegistry::class);
    }

    public function testAdd()
    {
        $module = $this->createMock(ModuleInterface::class);
        $module
            ->expects($this->atLeastOnce())
            ->method('getName')
            ->willReturn('my_module')
        ;

        $registry = new ModuleRegistry();
        $registry->add($module);
        $registry->freeze();

        // The registry should return added modules
        $this->assertEquals($module, $registry->get('my_module'));
    }

    public function testAddOnFrozenRegistry()
    {
        $module = $this->createMock(ModuleInterface::class);

        $registry = new ModuleRegistry();
        $registry->freeze();

        $this->assertExceptionRaised(Exception::class, function () use ($registry, $module) {
            // The registry should throw an exception when adding a module on a frozen registry
            $registry->add($module);
        });

    }

    public function testAddExistingModule()
    {
        $module = $this->createMock(ModuleInterface::class);

        $registry = new ModuleRegistry();
        $registry->add($module);

        $this->assertExceptionRaised(Exception::class, function () use ($registry, $module) {
            // The registry should throw an exception when adding a module twice
            $registry->add($module);
        });
    }

    public function testGet()
    {
        $module = $this->createMock(ModuleInterface::class);
        $module
            ->expects($this->atLeastOnce())
            ->method('getName')
            ->willReturn('my_module')
        ;

        $registry = new ModuleRegistry();
        $registry->add($module);
        $registry->freeze();

        // The registry should return added modules
        $this->assertEquals($module, $registry->get('my_module'));
    }

    public function testGetOnNonFrozen()
    {
        $module = $this->createMock(ModuleInterface::class);
        $module
            ->expects($this->atLeastOnce())
            ->method('getName')
            ->willReturn('my_module')
        ;

        $registry = new ModuleRegistry();
        $registry->add($module);

        $this->assertExceptionRaised(Exception::class, function () use ($registry, $module) {
            // The registry should throw an exception if the registry is not frozen
            $this->assertEquals($module, $registry->get('my_module'));
        });
    }

    public function testGetInvalidModule()
    {
        $module = $this->createMock(ModuleInterface::class);

        $registry = new ModuleRegistry();
        $registry->freeze();

        $this->assertExceptionRaised(Exception::class, function () use ($registry, $module) {
            // The registry should return added modules
            $this->assertEquals($module, $registry->get('my_module'));
        });
    }

    public function testFreeze()
    {
        $registry = new ModuleRegistry();
        $registry->freeze();

        $this->assertTrue($registry->isFrozen());
    }

    public function testFreezeOnFrozenRegistry()
    {
        $registry = new ModuleRegistry();
        $registry->freeze();

        $this->assertExceptionRaised(Exception::class, function () use ($registry) {
            $registry->freeze();
        });
    }

    public function testAll()
    {
        $module = $this->createMock(ModuleInterface::class);
        $module
            ->expects($this->atLeastOnce())
            ->method('getName')
            ->willReturn('my_module')
        ;

        $registry = new ModuleRegistry();
        $registry->add($module);
        $registry->freeze();

        $this->assertArrayHasKey('my_module', $registry->all());
        $this->assertContains($module, $registry->all());
    }

    public function testAllOnNonFrozenRegistry()
    {
        $module = $this->createMock(ModuleInterface::class);
        $module
            ->expects($this->atLeastOnce())
            ->method('getName')
            ->willReturn('my_module')
        ;

        $registry = new ModuleRegistry();
        $registry->add($module);

        $this->assertExceptionRaised(Exception::class, function () use ($registry) {
            $registry->all();
        });
    }
}
