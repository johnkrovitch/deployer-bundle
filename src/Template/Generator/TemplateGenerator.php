<?php

namespace JK\DeployBundle\Template\Generator;

use JK\DeployBundle\Exception\Exception;
use JK\DeployBundle\Template\TemplateInterface;
use JK\DeployBundle\Template\Twig\AppendTemplate;
use JK\DeployBundle\Template\Twig\PlaceholderTemplate;
use Symfony\Component\Filesystem\Filesystem;

class TemplateGenerator implements TemplateGeneratorInterface
{
    /**
     * @var string
     */
    private $rootDirectory;

    /**
     * @var Filesystem
     */
    private $fileSystem;

    public function __construct(string $rootDirectory)
    {
        $this->rootDirectory = $rootDirectory;
        $this->fileSystem = new Filesystem();
    }

    public function generate(TemplateInterface $template): void
    {
        if ($template instanceof PlaceholderTemplate) {
            $content = $this->replaceContent($template);
            $path = $this->rootDirectory.'/'.$template->getTarget();

            $this
                ->fileSystem
                ->dumpFile($path, $content)
            ;

            return;
        }

        if ($template instanceof AppendTemplate) {
            die('ok');
        }

        throw new Exception('The template class "'.get_class($template).'" is not supported');

    }

    private function replaceContent(TemplateInterface $template): string
    {
        $content = file_get_contents($template->getSource());

        foreach ($template->getParameters() as $name => $value) {
            $content = str_replace('{{ '.$name.' }}', $value, $content);
        }

        return $content;
    }
}
