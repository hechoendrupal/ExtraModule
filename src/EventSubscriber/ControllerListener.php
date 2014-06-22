<?php

namespace Drupal\ExtraModule\EventSubscriber;

use Doctrine\Common\Annotations\Reader;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Entity\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\Core\Controller\ControllerResolverInterface;

class ControllerListener implements EventSubscriberInterface
{
  /**
   * @var Reader
   */
  protected $reader;

  /**
   * Constructor.
   *
   * @param Reader $reader An Reader instance
   */
  public function __construct(
    Reader $reader,
    \Traversable $namespaces,
    AccountInterface $account,
    ControllerResolverInterface $controller_resolver,
    EntityManagerInterface $entity_manager
  )
  {
      $this->reader = $reader;
      $this->namespace = $namespaces;
      $this->account = $account;
      $this->controller_resolver = $controller_resolver;
      $this->entity_manager = $entity_manager;
  }

  /**
   * Modifies the Request object to apply configuration information found in
   * controllers annotations like the template to render or HTTP caching
   * configuration.
   *
   * @param FilterControllerEvent $event A FilterControllerEvent instance
   */
  public function onKernelController(FilterControllerEvent $event)
  {
    if (!is_array($controller = $event->getController())) {
      return;
    }

    $request  = $event->getRequest();
    $_content = $request->attributes->get('_content');
    if ($_content) {
      $controller = $this->controller_resolver->getControllerFromDefinition($_content);
    }

    $className = ClassUtils::getClass($controller[0]);
    $object    = new \ReflectionClass($className);
    $method    = $object->getMethod($controller[1]);

    // Register ExtraModule annotations
    \Doctrine\Common\Annotations\AnnotationRegistry::registerFile(
      dirname(__DIR__).'/Annotation/Permission.php'
    );

    $roles = $this->account->getRoles();
    $user_roles = $this->entity_manager
      ->getStorage('user_role')
        ->loadMultiple($roles);

    $has_permission = false;
    foreach ($this->reader->getMethodAnnotations($method) as $configuration) {
      foreach ($user_roles as $user_role) {
        $p = $configuration->getPermission();
        if ($user_role->hasPermission($p) || in_array('administrator', $roles)) {
          $has_permission = true;
        }
      }
      if (!$has_permission) {
        throw new AccessDeniedHttpException();
      }
    }
  }

  public static function getSubscribedEvents()
  {
    return array(
      KernelEvents::CONTROLLER => 'onKernelController',
    );
  }

}
