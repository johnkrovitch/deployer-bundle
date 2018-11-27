#!/usr/bin/env php
<?php

use JK\DeployBundle\Command\GenerateConfigurationCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

require __DIR__.'/../vendor/autoload.php';

$container = new ContainerBuilder();

$bundle = new \JK\DeployBundle\JKDeployBundle();
$bundle->build($container);

$extension = $bundle->getContainerExtension();
$container->registerExtension($extension);
$container->loadFromExtension($extension->getAlias());

$loader = new Twig_Loader_Filesystem(__DIR__.'/../src/Resources/views');
$twig = new Twig_Environment($loader, [
    'cache' => __DIR__.'/../var/cache/twig',
]);
$container->set('twig', $twig);

$container->compile();

$generateCommand = new GenerateConfigurationCommand();
$generateCommand->setContainer($container);

$application = new Application();
$application->add($generateCommand);
$application->run();
