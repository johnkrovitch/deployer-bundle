<?php

namespace JK\DeployBundle\Command;

use JK\DeployBundle\Configuration\ApplicationConfiguration;
use JK\DeployBundle\Module\EnvironmentModuleInterface;
use JK\DeployBundle\Module\Registry\ModuleRegistry;
use JK\DeployBundle\Module\Registry\ModuleRegistryInterface;
use JK\DeployBundle\Template\Generator\TemplateGenerator;
use JK\DeployBundle\Template\Twig\PlaceholderTemplate;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ParameterBag\FrozenParameterBag;
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
                InputOption::VALUE_OPTIONAL,
                'Clean the deploy directory',
                false
            )
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Generating deploy configuration');

        /** @var ModuleRegistryInterface $registry */
        $registry = $this->container->get(ModuleRegistry::class);
        $environmentVars = [];

        if ($input->hasOption('clean')) {
            $this->clean($input->getOption('directory'));
        }

        $configuration = $this->createApplicationConfiguration([
            'root_directory' => $input->getOption('directory'),
        ]);
        $io->text('Configuring modules...');

        foreach ($registry->all() as $module) {
            $module->configure($configuration);
        }
        $io->text('Configuring modules...[<info>OK</info>]');
        $io->text('Collecting environment parameters...');

        foreach ($registry->all() as $module) {
            $questions = $module->getQuestions();
            $answers = [];

            foreach ($questions as $parameterName => $question) {
                $answers[$parameterName] = $io->askQuestion($question);
            }
            $parameters = $module->collect($answers);

            foreach ($parameters as $name => $value) {
                $environmentVars[$module->getName().'.'.$name] = $value;
            }
        }
        $io->text('Collecting environment parameters...[<info>OK</info>]');

        $io->text('Collecting templates...');
        $templates = [];

        foreach ($registry->all() as $module) {

            if ($module instanceof EnvironmentModuleInterface) {
                $module->setEnv($environmentVars);
            }

            foreach ($module->getTemplates() as $template) {
                $templates[] = $template;
            }
        }
        $io->text('Collecting templates...[<info>OK</info>]');

        $generator = new TemplateGenerator($configuration->get('root_directory'));
        $io->text('Generating deployment files...');

        foreach ($templates as $template) {

            if (!$configuration->get('deploy_tasks') && PlaceholderTemplate::TYPE_DEPLOY === $template->getType()) {
                continue;
            }

            if (!$configuration->get('install_tasks') && PlaceholderTemplate::TYPE_INSTALL === $template->getType()) {
                continue;
            }

            if (!$configuration->get('rollback_tasks') && PlaceholderTemplate::TYPE_ROLLBACK === $template->getType()) {
                continue;
            }

            if (!$configuration->get('extra_tasks') && PlaceholderTemplate::TYPE_EXTRA === $template->getType()) {
                continue;
            }

            $generator->generate($template);
            $io->text('Generating '.$template->getTarget().'...[<info>OK</info>]');
        }
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

        }
    }
}
