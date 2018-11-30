<?php

namespace JK\DeployBundle\Cache;

use JK\DeployBundle\Exception\Exception;
use Symfony\Component\Filesystem\Filesystem;

class Cache implements CacheInterface
{
    /**
     * @var string
     */
    private $cacheFile;

    /**
     * @var Filesystem
     */
    private $fileSystem;

    /**
     * Cache constructor.
     *
     * @param string $cacheDirectory
     *
     * @throws \Exception
     */
    public function __construct(string $cacheDirectory)
    {
        $this->fileSystem = new Filesystem();

        if (!$this->fileSystem->exists($cacheDirectory)) {
            throw new Exception('The cache directory "'.$cacheDirectory.'" does not exists');
        }
        $this->cacheFile = $cacheDirectory.'/deploy.cache';

        if (!$this->fileSystem->exists($this->cacheFile)) {
            $this->fileSystem->touch($this->cacheFile);
        }
    }

    public function set(string $key, $data): void
    {
        $cache = $this->all();
        $cache[$key] = $data;

        $this->fileSystem->dumpFile($this->cacheFile, serialize($cache));
    }

    public function get(string $key)
    {
        $cache = $this->all();

        if (!key_exists($key, $cache)) {
            return null;
        }

        return $cache[$key];
    }

    public function all(): array
    {
        $cache = unserialize(file_get_contents($this->cacheFile));

        if (false === $cache) {
            $cache = [];
        }

        return $cache;
    }

    public function clear(): void
    {
        $this->fileSystem->dumpFile($this->cacheFile, serialize([]));
    }
}
