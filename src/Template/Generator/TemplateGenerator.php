<?php

namespace JK\DeployBundle\Template\Generator;

use JK\DeployBundle\Exception\Exception;
use JK\DeployBundle\Template\TemplateInterface;
use JK\DeployBundle\Template\Twig\TwigTemplate;
use Symfony\Component\Filesystem\Filesystem;
use Twig_Environment;

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

    /**
     * @var Twig_Environment
     */
    private $twig;

    public function __construct(string $rootDirectory, Twig_Environment $twig)
    {
        $this->rootDirectory = $rootDirectory;
        $this->fileSystem = new Filesystem();
        $this->twig = $twig;
    }

    /**
     * @param TemplateInterface $template
     *
     * @throws Exception
     */
    public function generate(TemplateInterface $template): void
    {
        if (!$template instanceof TwigTemplate) {
            throw new Exception('The template class "'.get_class($template).'" is not supported');
        }
        $path = $this->rootDirectory.$template->getTarget();

        if ($template->appendToFile()) {
            $content = $this->twig->render($template->getSource(), $template->getParameters());

            if (!$this->fileSystem->exists($path)) {
                $this->fileSystem->touch($path);
            }
            $targetContent = file_get_contents($path);

            if (false === strstr($targetContent, $content)) {
                $this->fileSystem->appendToFile($path, PHP_EOL.$content);
            }
        } else {
            $content = $this->twig->render($template->getSource(), $template->getParameters());
            $this
                ->fileSystem
                ->dumpFile($path, $content)
            ;
        }
    }
}
