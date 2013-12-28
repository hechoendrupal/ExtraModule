<?php

namespace Drupal\ExtraModule\Controller;

use Drupal\Core\Controller\ControllerBase;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;

class DefaultController extends ControllerBase implements ContainerInjectionInterface {


  public function __constructor() {
  }

  public static function create(ContainerInterface $container){
    return new static(
    );
  }
  
  /**
   * helloAction
   * @param  string $name Get
   * @return [type]       [description]
   */
  public function helloAction($name) {
    return "Hello " . $name . "!";
  }
}
