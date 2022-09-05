<?php

namespace Battis\UserSession;

use DI\Container;
use ReflectionClass;
use Slim\Views\PhpRenderer;

use function DI\autowire;
use function DI\get;

class Dependencies
{
  const TEMPLATE_PATH = "battis.userSession.dependencies.templatePath";

  private static function setDefaults(Container $container)
  {
    $reflection = new ReflectionClass(self::class);
    $projectRoot = dirname($reflection->getFileName(), 2);

    foreach (
      [
        self::TEMPLATE_PATH => "$projectRoot/templates",
      ]
      as $key => $value
    ) {
      if (false == $container->has($key)) {
        $container->set($key, $value);
      }
    }
  }

  public static function prepare(Container $container)
  {
    self::setDefaults($container);

    // prepare Slim PHP template renderer (for login & authorize endpoints)
    if (!$container->has(PhpRenderer::class)) {
      $container->set(
        PhpRenderer::class,
        autowire()->constructorParameter(
          "templatePath",
          get(self::TEMPLATE_PATH)
        )
      );
    }
  }
}
