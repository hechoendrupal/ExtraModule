ExtraModule
===========

ExtraModule is a module for Drupal 8 to add some tools for developers

Usage
-----

* Download and enable module
* Use your awesome powers

#### @Permission Annotation
----------------------
```php

class DefaultController extends ControllerBase{
  //...
  /**
   * helloAction
   * @param  string $name 
   *
   * @Permission("access content")
   */
  public function helloAction($name) {
    return "Hello " . $name . "!";
  }
  //...
}
```


#### Call twig template like Symfony
-----------------------------


```django
{# hello.html.twig #}

{% extends "module_name::base.html.twig" %}

{% block hello_wrapper %}
  Hellos {{name}}
{% endblock %}

```

Roadmap
=======

* Template annotation

