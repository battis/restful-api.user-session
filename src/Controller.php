<?php

namespace Battis\UserSession;

use Battis\UserSession\Actions;
use Slim\Middleware\Session;

class Controller
{
    const ENDPOINT = "/auth";

    public function __invoke($app)
    {
        $app->group("/", function ($app) {
            $app->post("login", Actions\HandleLogin::class);
            $app->get("login", Actions\ShowLoginView::class);
            $app->any("logout", Actions\HandleLogout::class);
        })->add(Session::class);
    }
}
