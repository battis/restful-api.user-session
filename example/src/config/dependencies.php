<?php

use Battis\UserSession;
use Battis\UserSession\Repositories\UserRepositoryInterface;
use DI\Container;
use Example\UserRepository;
use Slim\Views\PhpRenderer;

use function DI\autowire;
use function DI\create;

/** @var Container $container */

$container->set(UserRepositoryInterface::class, create(UserRepository::class));
$container->set(
  PhpRenderer::class,
  autowire()->constructorParameter("templatePath", __DIR__ . "/../../templates")
);

UserSession\Dependencies::prepare($container);
