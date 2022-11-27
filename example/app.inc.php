<?php

use Battis\UserSession\Dependencies;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

require_once __DIR__ . "/vendor/autoload.php";

$container = (new ContainerBuilder())
    ->addDefinitions(Dependencies::definitions())
    ->addDefinitions(include __DIR__ . "/config/settings.inc.php")
    ->addDefinitions(include __DIR__ . "/config/dependencies.inc.php")
    ->build();

$app = AppFactory::createFromContainer($container);

include __DIR__ . "/config/middleware.inc.php";
include __DIR__ . "/config/routes.inc.php";

$app->run();
