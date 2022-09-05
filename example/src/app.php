<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

require_once __DIR__ . "/../vendor/autoload.php";

$container = (new ContainerBuilder())
  ->addDefinitions(include __DIR__ . "/config/settings.php")
  ->build();

$app = AppFactory::createFromContainer($container);

include __DIR__ . "/config/dependencies.php";
include __DIR__ . "/config/middleware.php";
include __DIR__ . "/config/routes.php";

$app->addErrorMiddleware(true, true, true);

$app->run();
