<?php

namespace Battis\UserSession;

use Battis\UserSession\Entities\UserEntityInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use SlimSession\Helper as Session;

class Manager
{
  private const USER = "battis.userSession.manager.user";
  private const REDIRECT = "battis.userSession.manager.redirect";

  private $session;
  private $loginPath;
  private $defaultRedirect;

  public function __construct(
    Session $session,
    string $loginPath = "/auth/login",
    string $defaultRedirect = "/"
  ) {
    $this->session = $session;
    $this->loginPath = $loginPath;
    $this->defaultRedirect = $defaultRedirect;
  }

  public function startUserLogin(RequestInterface $request): ResponseInterface
  {
    $this->session->set(self::REDIRECT, $request->getUri()->getPath());
    $response = new Response();
    return $response->withHeader("Location", $this->loginPath)->withStatus(302); // using status 302 forces the redirect to use the GET method
  }

  public function startUserSession(
    UserEntityInterface $user = null
  ): ResponseInterface {
    if (!empty($user)) {
      $this->session->set(self::USER, $user);
    }
    $redirect = $this->session->get(self::REDIRECT, $this->defaultRedirect);
    $this->session->delete(self::REDIRECT);
    $response = new Response();
    return $response->withHeader("Location", $redirect)->withStatus(302);
  }

  public function sessionIsActive(): bool
  {
    return !empty($this->session->get(self::USER));
  }

  public function getCurrentUser(): ?UserEntityInterface
  {
    return $this->session->get(self::USER, null);
  }

  public function endUserSession(): void
  {
    $this->session->destroy();
  }
}
