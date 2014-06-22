<?php

namespace Drupal\ExtraModule\Twig;

use Drupal\Core\Extension\ModuleHandlerInterface;

class TwigFilesystemLoader extends \Twig_Loader_Filesystem
{

  const SHORT_NAME = '/^([^:]*):([^:]*):(.+)\.([^\.]+)\.([^\.]+)$/';

  /**
   * locator
   * @var [type]
   */
    protected $locator;

  /**
   * Parser
   * @var [type]
   */
  protected $parser;

  public function __construct($drupal_root)
  {
    parent::__construct($drupal_root);
  }

  /**
   * Search template
   * @param  string $template Template
   * @return [type]           [description]
   */
  protected function findTemplate($template)
  {
    $logicalName = (string) $template;

    if (isset($this->cache[$logicalName])) {
      return $this->cache[$logicalName];
    }

    $file = null;
    $previous = null;

    try {
      // find the absolute path
      $file = parent::findTemplate($template);
    } catch (\Twig_Error_Loader $e) {
      $previous = $e;

      try {
        $name = $template;
        if (false !== strpos($name, '..')) {
          throw new \RuntimeException(sprintf('Template name "%s" contains invalid characters.', $name));
        }

        if (!preg_match(self::SHORT_NAME, $name, $matches)) {
          throw new \InvalidArgumentException(sprintf('Template name "%s" is not valid (format is "module::templates/template.html.twig").', $name));
        }

        list(,$module,,$template,$html,$twig) = $matches;

        $path = drupal_get_path('module', $module) ? drupal_get_path('module', $module) : drupal_get_path('theme', $module);

        if (!empty($path)) {
          $file = $path . '/templates/' . $template .'.'. $html . '.' . $twig;
        }

      } catch (\Exception $e) {
        $previous = $e;
      }
    }

    if (false === $file || null === $file) {
      throw new \Twig_Error_Loader(sprintf('Unable to find template "%s".', $logicalName), -1, null, $previous);
    }

    return $this->cache[$logicalName] = $file;
  }
}
