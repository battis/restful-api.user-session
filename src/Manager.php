<?php

namespace Battis\UserSession;

use Battis\UserSession\Entities\UserEntityInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use SlimSession\Helper as Session;

class Manager
{
    public const DEFAULT_LOGIN_PATH = "/auth/login";
    public const DEFAULT_REDIRECT = "/";

    private const HEADER_LOCATION = "Location";

    private const USER = "battis.userSession.manager.user";
    private const REDIRECT = "battis.userSession.manager.redirect";

    private $session;
    private $loginPath;
    private $defaultRedirect;

    public function __construct(
        Session $session,
        string $loginPath = self::DEFAULT_LOGIN_PATH,
        string $defaultRedirect = self::DEFAULT_REDIRECT
    ) {
        $this->session = $session;
        $this->loginPath = $loginPath;
        $this->defaultRedirect = $defaultRedirect;
    }

    public function startUserLogin(RequestInterface $request): ResponseInterface
    {
        $response = new Response();
        if ($this->sessionIsActive()) {
            return $response
                ->withHeader(self::HEADER_LOCATION, $this->defaultRedirect)
                ->withStatus(302);
        } else {
            $this->session->set(self::REDIRECT, $request->getUri()->getPath());
            return $response
                ->withHeader(self::HEADER_LOCATION, $this->loginPath)
                ->withStatus(302); // using status 302 forces the redirect to use the GET method
        }
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
        return $response
            ->withHeader(self::HEADER_LOCATION, $redirect)
            ->withStatus(302);
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
        $this->session->clear();
    }
}
