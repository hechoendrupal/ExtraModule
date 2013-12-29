<?php

namespace Drupal\ExtraModule\EventSubscriber;

use Doctrine\Common\Annotations\Reader;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\Common\Util\ClassUtils;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ControllerListener implements EventSubscriberInterface{

  /**
   * @var Reader
   */
  protected $reader;

  /**
   * Constructor.
   *
   * @param Reader $reader An Reader instance
   */
  public function __construct(Reader $reader, \Traversable $namespaces, AccountInterface $account){
      $this->reader = $reader;
      $this->namespace = $namespaces;
      $this->account = $account;
  }

  /**
   * Modifies the Request object to apply configuration information found in
   * controllers annotations like the template to render or HTTP caching
   * configuration.
   *
   * @param FilterControllerEvent $event A FilterControllerEvent instance
   */
  public function onKernelController(FilterControllerEvent $event){

    if (!is_array($controller = $event->getController())) {
      return;
    }

    $className = class_exists('Doctrine\Common\Util\ClassUtils') ? ClassUtils::getClass($controller[0]) : get_class($controller[0]);
    $object    = new \ReflectionClass($className);
    $method    = $object->getMethod($controller[1]);

    \Doctrine\Common\Annotations\AnnotationRegistry::registerAutoloadNamespace(
        'Drupal\ExtraModule\Annotation',
        dirname(__DIR__).'/../../'
    );

    $user_roles = $this->account->getRoles();
    $user_roles = entity_load_multiple('user_role', $user_roles);

    $has_permission = false;
    foreach ($this->reader->getMethodAnnotations($method) as $configuration) {
      foreach ($user_roles as $user_role) {

        if ($user_role->hasPermission($configuration->getPermission())){
          $has_permission = true;
        }

      }

      if (!$has_permission){
        throw new AccessDeniedHttpException();
      }
    }


  }

  public static function getSubscribedEvents() {
    return array(
      KernelEvents::CONTROLLER => 'onKernelController',
    );
  }

}
