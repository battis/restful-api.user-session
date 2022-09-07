<?php

use Battis\UserSession;
use DI\Container;
use Example\PageRenderer;
use Slim\App;

/** @var App $app */
/** @var Container $container */

// assign the UserSession endpoint(s) to the UserSession\Controller
$app->group(UserSession\Controller::ENDPOINT, UserSession\Controller::class);

$app
  ->get("/home", PageRenderer::class)
  ->add(UserSession\Middleware\Session::class);
$app->redirect("/", "/home");
$app
  ->get("/protected", PageRenderer::class)
  ->add(UserSession\Middleware\RequireAuthentication::class);
