<?php

namespace Drupal\ExtraModule;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class ExtraModuleServiceCompilerPass implements CompilerPassInterface
{
  /**
   * Change services in @service_container
   *
   * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
   *   The container to process.
   */
  public function process(ContainerBuilder $container)
  {
    $definition = $container->getDefinition('twig.loader.filesystem');
    $definition->setClass('Drupal\ExtraModule\Twig\TwigFilesystemLoader');
  }
}
