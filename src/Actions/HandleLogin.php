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
  private $usernameFieldName;
  private $passwordFieldName;

  public function __construct(
    Manager $manager,
    UserRepositoryInterface $userRepository,
    PhpRenderer $renderer,
    string $usernameFieldName = "username",
    string $passwordFiledName = "password"
  ) {
    $this->manager = $manager;
    $this->userRepository = $userRepository;
    $this->renderer = $renderer;
    $this->usernameFieldName = $usernameFieldName;
    $this->passwordFieldName = $passwordFiledName;
  }

  public function __invoke(ServerRequest $request, Response $response)
  {
    if (
      ($user = $this->userRepository->getUserEntityByUsername(
        $request->getParsedBodyParam($this->usernameFieldName)
      )) &&
      $user->passwordVerify(
        $request->getParsedBodyParam($this->passwordFieldName)
      )
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
