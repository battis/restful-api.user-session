<?php

namespace Battis\UserSession\Middleware;

use Battis\UserSession\Manager;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;

class RequireAuthentication extends Session
{
  private $manager;

  public function __construct($settings = [], Manager $manager)
  {
    parent::__construct($settings);
    $this->manager = $manager;
  }

  public function __invoke(
    ServerRequestInterface $request,
    RequestHandlerInterface $handler
  ): ResponseInterface {
    $this->startSession();

    // check if a user session exists
    if ($this->manager->sessionIsActive()) {
      return $handler->handle($request);
    } else {
      return $this->manager->startUserLogin($request);
    }
  }
}
