<?php

namespace JK\DeployBundle\Command;

use JK\DeployBundle\Cache\Cache;
use JK\DeployBundle\Configuration\ApplicationConfiguration;
use JK\DeployBundle\Module\EnvironmentModuleInterface;
use JK\DeployBundle\Module\Registry\ModuleRegistry;
use JK\DeployBundle\Module\Registry\ModuleRegistryInterface;
use JK\DeployBundle\Module\TaskModuleInterface;
use JK\DeployBundle\Template\Generator\TemplateGenerator;
use JK\DeployBundle\Template\TemplateInterface;
use JK\DeployBundle\Template\Twig\TwigTemplate;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Finder\Finder;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenerateConfigurationCommand extends Command implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function configure()
    {
        $this
            ->setName('deploy:generate-configuration')
            ->addOption(
                'directory',
                'd',
                InputOption::VALUE_OPTIONAL,
                'The directory where the deploy files will be generated'
            )
            ->addOption(
                'clean',
                'c',
                InputOption::VALUE_NONE,
                'Clean the deploy directory'
            )
            ->addOption(
                'clear-cache',
                'cc',
                InputOption::VALUE_NONE,
                'Remove the cache before running the command'
            )
            ->addOption(
                'prefix',
                'p',
                InputOption::VALUE_OPTIONAL,
                '...',
                'etc/ansible'
            )
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Generating deploy configuration');

        $cache = $this->container->get(Cache::class);

        if ($input->getOption('clear-cache')) {
            $cache->clear();
        }

        /** @var ModuleRegistryInterface $registry */
        $registry = $this->container->get(ModuleRegistry::class);
        $environmentVars = [];

        if ($input->getOption('clean')) {
            $this->clean($input->getOption('directory'));
        }

        $configuration = $this->createApplicationConfiguration([
            'root_directory' => $input->getOption('directory'),
            'prefix' => $input->getOption('prefix'),
        ]);
        $io->write(' Configuring modules...');

        foreach ($registry->all() as $module) {
            $module->configure($configuration);
        }
        $io->write('[<info>OK</info>]');
        $io->newLine();
        $io->write(' Collecting environment parameters...');

        if (0 === count($cache->all())) {
            foreach ($registry->all() as $module) {
                $questions = $module->getQuestions();
                $answers = [];

                foreach ($questions as $name => $question) {
                    $answer = $io->askQuestion($question);
                    $answers[$name] = $answer;
                    $cache->set($module->getName().'.'.$name, $answer);
                }
                $parameters = $module->collect($answers);

                foreach ($parameters as $name => $value) {
                    $environmentVars[$module->getName().'.'.$name] = $value;
                }
            }
            $io->write('[<info>OK</info>]');
            $io->newLine();
        } else {
            $io->newLine();
            $io->write(' Loading data from cache...');
            $answers = $cache->all();

            foreach ($registry->all() as $module) {
                $collectedData = [];

                foreach ($answers as $name => $answer) {
                    $data = explode('.', $name);

                    if ($data[0] === $module->getName()) {
                        $collectedData[$data[1]] = $answer;
                    }
                    $environmentVars[$name] = $answer;
                }
                $parameters = $module->collect($collectedData);

                foreach ($parameters as $name => $value) {
                    $environmentVars[$module->getName().'.'.$name] = $value;
                }
            }
            $io->write('[<info>OK</info>]');
            $io->newLine();
        }
        $io->write(' Collecting templates...');
        $templates = [];
        $lateModules = [];

        foreach ($registry->all() as $module) {
            if ($module instanceof EnvironmentModuleInterface) {
                $module->setEnv($environmentVars);
            }
            if ($module instanceof TaskModuleInterface) {
                $lateModules[] = $module;
                continue;
            }
            foreach ($module->getTemplates() as $template) {
                $templates[] = $template;
            }
        }
        $io->write('[<info>OK</info>]');
        $io->newLine();

        $io->text('Generating deployment files...');
        $tasks = $this->generateTemplates($templates, $configuration, $io);
        $lateTemplates = [];

        foreach ($lateModules as $module) {
            $module->setTasks($tasks);
            $lateTemplates = array_merge($lateTemplates, $module->getTemplates());
        }

        $this->generateTemplates($lateTemplates, $configuration, $io);

        $io->text('Generating deployment files...[<info>OK</info>]');
    }

    private function createApplicationConfiguration(array $data): ApplicationConfiguration
    {
        $resolver = new OptionsResolver();

        $configuration = new ApplicationConfiguration();
        $configuration->configureOptions($resolver);
        $configuration->setParameters($resolver->resolve($data));

        return $configuration;
    }

    private function clean(string $rootDirectory)
    {
        $finder = new Finder();
        $finder
            ->files()
            ->in($rootDirectory)
        ;

        foreach ($finder as $fileInfo) {
            unlink($fileInfo->getRealPath());
        }
    }

    /**
     * @param TemplateInterface[]      $templates
     * @param ApplicationConfiguration $configuration
     * @param SymfonyStyle             $io
     *
     * @return array
     */
    private function generateTemplates(
        array $templates,
        ApplicationConfiguration $configuration,
        SymfonyStyle $io
    ): array {
        $tasks = [];
        $generator = new TemplateGenerator($configuration->get('root_directory'), $this->container->get('twig'));

        usort($templates, function (TemplateInterface $template1, TemplateInterface $template2) {
            return $template1->getPriority() >= $template2->getPriority();
        });

        foreach ($templates as $template) {
            if (!$configuration->get('deploy_tasks') && TwigTemplate::TYPE_DEPLOY === $template->getType()) {
                continue;
            }

            if (!$configuration->get('install_tasks') && TwigTemplate::TYPE_INSTALL === $template->getType()) {
                continue;
            }

            if (!$configuration->get('rollback_tasks') && TwigTemplate::TYPE_ROLLBACK === $template->getType()) {
                continue;
            }

            if (!$configuration->get('extra_tasks') && TwigTemplate::TYPE_EXTRA === $template->getType()) {
                continue;
            }

            $generator->generate($template);

            $tasks[$template->getType()][] = $template->getTarget();

            $io->text('  |__'.$template->getTarget().'...[<info>OK</info>]');
        }

        return $tasks;
    }
}
