<?php

namespace Battis\UserSession\Actions;

use Battis\UserSession\Manager;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\PhpRenderer;

class ShowLoginView
{
    private $renderer;
    private $manager;

    public function __construct(PhpRenderer $renderer, Manager $manager)
    {
        $this->renderer = $renderer;
        $this->manager = $manager;
    }

    public function __invoke(
        RequestInterface $request,
        ResponseInterface $response
    ) {
        if ($this->manager->sessionIsActive()) {
            return $this->manager->startUserSession();
        }
        return $this->renderer->render($response, "login.php");
    }
}
