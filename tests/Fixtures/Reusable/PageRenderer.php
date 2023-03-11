<?php

namespace Battis\UserSession\Tests\Fixtures\Reusable;

use Battis\UserSession\Manager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\PhpRenderer;

/**
 * Render a web page using a template with the same name as the
 * endpoint. Current user and query and URL params are passed as
 * template variables.
 */
class PageRenderer
{
    private $renderer;
    private $manager;

    public function __construct(PhpRenderer $renderer, Manager $manager)
    {
        $this->renderer = $renderer;
        $this->manager = $manager;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args = []
    ) {
        return $this->renderer->render(
            $response,
            basename($request->getUri()->getPath()) . ".php",
            array_merge($args, $request->getQueryParams(), [
                "user" => $this->manager->getCurrentUser(),
            ])
        );
    }
}
