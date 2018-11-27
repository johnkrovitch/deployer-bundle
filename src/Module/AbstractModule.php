<?php

namespace JK\DeployBundle\Module;

use JK\DeployBundle\Configuration\ApplicationConfiguration;
use JK\DeployBundle\Exception\Exception;
use JK\DeployBundle\Template\TemplateInterface;
use JK\DeployBundle\Template\Twig\PlaceholderTemplate;
use Symfony\Component\Filesystem\Filesystem;

abstract class AbstractModule implements ModuleInterface
{
    const PRIORITY_INITIALIZE = -100;
    const PRIORITY_SOURCE = 0;
    const PRIORITY_APPLICATION = 100;
    const PRIORITY_FINALIZE = 200;

    /**
     * @var Filesystem
     */
    protected $fileSystem;

    protected $rootDirectory = '';

    public function configure(ApplicationConfiguration $configuration): void
    {
        $this->fileSystem = new Filesystem();
        $rootDirectory = $configuration->get('root_directory');

        if (!$this->fileSystem->exists($rootDirectory)) {
            throw new Exception('The root directory "'.$rootDirectory.'" does not exists or it is not readable');
        }

        $this->rootDirectory = $rootDirectory;
    }

    public function getQuestions(): array
    {
        return [];
    }

    public function collect(array $values): array
    {
        return [];
    }

    public function getPriority(): int
    {
        return self::PRIORITY_APPLICATION;
    }

    public function getTemplates(): array
    {
        return [];
    }

    /**
     * @param string $resource
     *
     * @return bool|string
     *
     * @throws Exception
     */
    protected function getResourcePath(string $resource)
    {
        $path = realpath(__DIR__.'/../Resources/views/'.$resource);

        if (false === $path) {
            throw new Exception('The resource "'.$resource.'" does not exists');
        }

        return $path;
    }

    protected function createTemplate(string $source, string $target, string $type, array $parameters = []
    ): PlaceholderTemplate
    {
        return new PlaceholderTemplate(
            $this->getResourcePath($source),
            $target,
            $type,
            $parameters
        );
    }

    protected function createDeployTemplate(
        string $source,
        string $target,
        array $parameters = []
    ): PlaceholderTemplate
    {
        return $this->createTemplate($source, $target, PlaceholderTemplate::TYPE_DEPLOY, $parameters);
    }

    protected function createInstallTemplate(
        string $source,
        string $target,
        array $parameters = []
    ): PlaceholderTemplate
    {
        return $this->createTemplate($source, $target, PlaceholderTemplate::TYPE_INSTALL, $parameters);
    }

    protected function createRollbackTemplate(
        string $source,
        string $target,
        array $parameters = []
    ): PlaceholderTemplate
    {
        return $this->createTemplate($source, $target, PlaceholderTemplate::TYPE_ROLLBACK, $parameters);
    }

    protected function createExtraTemplate(
        string $source,
        string $target,
        array $parameters = []
    ): PlaceholderTemplate
    {
        return $this->createTemplate($source, $target, PlaceholderTemplate::TYPE_EXTRA, $parameters);
    }
}
