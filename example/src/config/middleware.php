<?php

use DI\Container;
use Slim\App;

/** @var App $app */
/** @var Container $container */

// prepare the $container with any middleware you might plan on using

// use the Slim body-parsing middleware for convenience
$app->addBodyParsingMiddleware();
