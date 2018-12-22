<?php

namespace JK\DeployBundle\Tests\Fake;

use JK\DeployBundle\Module\AbstractModule;
use Symfony\Component\Filesystem\Filesystem;

class FakeModule extends AbstractModule
{
    public function getName(): string
    {
        return 'fake';
    }

    public function getRootDirectory(): string
    {
        return $this->rootDirectory;
    }

    public function getFilesystem(): Filesystem
    {
        return $this->fileSystem;
    }
}
