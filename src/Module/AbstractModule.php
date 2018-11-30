<?php

namespace JK\DeployBundle\Module;

use JK\DeployBundle\Configuration\ApplicationConfiguration;
use JK\DeployBundle\Exception\Exception;
use JK\DeployBundle\Template\CopyTemplate;
use JK\DeployBundle\Template\TemplateInterface;
use JK\DeployBundle\Template\Twig\TwigTemplate;
use Symfony\Component\Filesystem\Filesystem;

abstract class AbstractModule implements ModuleInterface
{
    /**
     * @var Filesystem
     */
    protected $fileSystem;

    /**
     * @var string
     */
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

    public function getTemplates(): array
    {
        return [];
    }

    protected function createTwigTemplate(
        string $source,
        string $target,
        string $type,
        array $parameters = [],
        int  $priority = TemplateInterface::PRIORITY_APPLICATION
    ): TemplateInterface
    {
        return new TwigTemplate(
            $source,
            $target,
            $type,
            $parameters,
            $priority
        );
    }

    protected function createCopyTemplate(
        string $source,
        string $target,
        string $type,
        int $priority = TemplateInterface::PRIORITY_APPLICATION,
        string $resourceRoot = null
    ): TemplateInterface {

        if (null === $resourceRoot) {
            $resourceRoot = __DIR__.'/../Resources/views/';
        }
        $source = $resourceRoot.$source;

        return new CopyTemplate(
            $source,
            $target,
            $type,
            $priority
        );
    }

    protected function createDeployTemplate(
        string $source,
        string $target,
        array $parameters = [],
        int  $priority = TemplateInterface::PRIORITY_APPLICATION
    ): TemplateInterface
    {
        return $this->createTwigTemplate($source, $target, TemplateInterface::TYPE_DEPLOY, $parameters, $priority);
    }

    protected function createInstallTemplate(
        string $source,
        string $target,
        array $parameters = [],
        int  $priority = TemplateInterface::PRIORITY_APPLICATION
    ): TemplateInterface
    {
        return $this->createTwigTemplate($source, $target, TemplateInterface::TYPE_INSTALL, $parameters, $priority);
    }

    protected function createRollbackTemplate(
        string $source,
        string $target,
        array $parameters = [],
        int  $priority = TemplateInterface::PRIORITY_APPLICATION
    ): TemplateInterface
    {
        return $this->createTwigTemplate($source, $target, TemplateInterface::TYPE_ROLLBACK, $parameters, $priority);
    }

    protected function createExtraTemplate(
        string $source,
        string $target,
        array $parameters = [],
        int  $priority = TemplateInterface::PRIORITY_APPLICATION
    ): TemplateInterface
    {
        return $this->createTwigTemplate($source, $target, TemplateInterface::TYPE_EXTRA, $parameters, $priority);
    }
}
