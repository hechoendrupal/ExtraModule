<?php

namespace Drupal\ExtraModule;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderInterface;

class ExtraModuleServiceProvider implements ServiceProviderInterface
{
  /**
   * {@inheritdoc}
   */
  public function register(ContainerBuilder $container)
  {
    // Add a compiler pass for adding Normalizers and Encoders to Serializer.
    $container->addCompilerPass(new ExtraModuleServiceCompilerPass());
  }

}
