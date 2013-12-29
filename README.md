ExtraModule
===========

ExtraModule is a module for Drupal 8 to add some tools for developers

Usage
==

* Download and enable module
* Use your awesome powers

```php

class DefaultController extends ControllerBase{
  
  /**
   * helloAction
   * @param  string $name 
   *
   * @Permission(permission="access content")
   */
  public function helloAction($name) {
    return "Hello " . $name . "!";
  }

}

```
Roadmap
=======

* Twig Filesystem
* Template annotation
* Permissions in YML file

