<?php

namespace Drupal\ExtraModule\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\ExtraModule\Annotation\Permission;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;

/**
 * @Permission(perm="Admin permission")
 */
class DefaultController extends ControllerBase implements ContainerInjectionInterface {

  public function __constructor() {
  }

  public static function create(ContainerInterface $container){
    return new static(
    );
  }

  /**
   * @Permission(permission="administer block")
   */
  public function helloAction($name) {
    return "Hello " . $name . "!";
  }
}
