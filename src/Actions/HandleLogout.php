<?php

namespace Battis\UserSession\Actions;

use Battis\UserSession\Manager;
use Fig\Http\Message\StatusCodeInterface as StatusCode;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;

class HandleLogout
{
    private $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ) {
        $this->manager->endUserSession();
        $response = new Response();
        return $response
            ->withHeader("Location", "/")
            ->withStatus(StatusCode::STATUS_TEMPORARY_REDIRECT);
    }
}
