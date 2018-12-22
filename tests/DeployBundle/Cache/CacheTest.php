<?php

namespace JK\DeployBundle\Tests\Cache;

use JK\DeployBundle\Cache\Cache;
use JK\DeployBundle\Exception\Exception;
use JK\DeployBundle\Tests\TestBase;

class CacheTest extends TestBase
{
    private $cacheDirectory = __DIR__.'/../../../var/cache/tests';

    public function setUp()
    {
        if (!file_exists($this->cacheDirectory)) {
            mkdir($this->cacheDirectory, 777, true);
        }

        if (file_exists($this->cacheDirectory.'/deploy.cache')) {
            unlink($this->cacheDirectory.'/deploy.cache');
        }
    }

    public function testConstruct()
    {
        $this->assertExceptionRaised(Exception::class, function () {
            new Cache('/invalid/path');
        });
    }

    public function testSetAndGet()
    {
        $cache = $this->createCache();

        $cache->set('test.key', [
            'value' => 666,
        ]);

        $this->assertEquals([
            'value' => 666,
        ], $cache->get('test.key'));
        $this->assertNull($cache->get('wrong'));
    }

    public function testAll()
    {
        $cache = $this->createCache();
        $cache->set('test.key', [
            'value' => 666,
        ]);
        $cache->set('second.key', [
            'value' => 999,
        ]);

        $this->assertEquals([
            'test.key' => [
                'value' => 666,
            ],
            'second.key' => [
                'value' => 999,
            ],
        ], $cache->all());
    }

    public function testClear()
    {
        $cache = $this->createCache();
        touch($this->cacheDirectory.'/deploy.cache');
        $cache->clear();

        $this->assertEquals([], $cache->all());
    }

    private function createCache(): Cache
    {
        $cache = new Cache($this->cacheDirectory);

        return $cache;
    }
}
