<?php

/**
 * @file
 * Contains \Drupal\ExtraModule\Annotation\Permission.
 */

namespace Drupal\ExtraModule\Annotation;

/**
 * Defines an Permission annotation object.
 *
 * @Annotation
 */
class Permission
{
  /**
   * permission
   * @var string
   */
  public $permission;

  public function getPermission()
  {
    return $this->permission;
  }

}
