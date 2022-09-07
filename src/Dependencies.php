<?php

namespace Battis\UserSession;

use Composer\Autoload\ClassLoader;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use Slim\Views\PhpRenderer;

class Dependencies
{
    const TEMPLATES = "battis.userSession.templates";

    private static $pathToApp;

    public static function definitions(
        string $pathToTemplates = "{PACKAGE_ROOT}/templates"
    ) {
        $pathToTemplates = self::expandPath($pathToTemplates);
        return [
            PhpRenderer::class => function (ContainerInterface $container) {
                return new PhpRenderer($container->get(self::TEMPLATES));
            },
        ];
    }

    private static function expandPath($path)
    {
        if (empty(self::$pathToApp)) {
            self::$pathToApp = dirname(
                (new ReflectionClass(ClassLoader::class))->getFileName(),
                3
            );
        }

        foreach (
            [
                "APP_ROOT" => self::$pathToApp,
                "PACKAGE_ROOT" =>
                    self::$pathToApp . "/vendor/battis/user-session",
            ]
            as $placeholder => $placeholderPath
        ) {
            $path = preg_replace(
                "/\{$placeholder\}/i",
                $placeholderPath,
                $path
            );
        }
        return $path;
    }
}
