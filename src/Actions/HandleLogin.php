<?php

namespace Battis\UserSession\Actions;

use Battis\UserSession\Manager;
use Battis\UserSession\Repositories\UserRepositoryInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest;
use Slim\Views\PhpRenderer;

class HandleLogin
{
    private $manager;
    private $userRepository;
    private $renderer;

    public function __construct(
        Manager $manager,
        UserRepositoryInterface $userRepository,
        PhpRenderer $renderer
    ) {
        $this->manager = $manager;
        $this->userRepository = $userRepository;
        $this->renderer = $renderer;
    }

    public function __invoke(ServerRequest $request, Response $response)
    {
        $user = $this->userRepository->getUserEntityByUsername(
            $request->getParsedBodyParam("username")
        );
        if (
            $user !== null &&
            $user->passwordVerify($request->getParsedBodyParam("password"))
        ) {
            return $this->manager->startUserSession($user);
        }

        // TODO add timeout
        return $this->renderer->render($response, "login.php", [
            "message" => "bad credentials",
            "message_type" => "error",
        ]);
    }
}
