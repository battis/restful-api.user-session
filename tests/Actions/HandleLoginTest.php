<?php

namespace Battis\UserSession\Tests\Actions;

use Battis\UserSession\Manager;
use Battis\UserSession\Repositories\UserRepositoryInterface;
use Battis\UserSession\Tests\Fixtures\Reusable\User;
use Battis\UserSession\Tests\Fixtures\Reusable\UserRepository;
use Battis\UserSession\Tests\TestCase;

class HandleLoginTest extends TestCase
{
    public function testHandleLogin()
    {
        $REDIRECT = "battis.userSession.manager.redirect";

        $app = $this->getAppInstance();
        $session = $this->getSession();
        $user = new User();
        $initialPath = "/foo/bar/baz";

        /** @var UserRepository $userRepo */
        $userRepo = $app->getContainer()->get(UserRepositoryInterface::class);
        $userRepo->addUser($user);

        /** @var Manager $manager */
        $manager = $app->getContainer()->get(Manager::class);
        $session->set($REDIRECT, $initialPath);

        $request = $this->createRequest(
            "POST",
            Manager::DEFAULT_LOGIN_PATH,
            [],
            [
                "username" => $user->username,
                "password" => $user->password,
            ]
        );
        $this->assertEquals($initialPath, $session->get($REDIRECT));
        $response = $app->handle($request);
        $this->assertFalse($session->__isset($REDIRECT));
        $this->assertTrue($manager->sessionIsActive());
        $this->assertEquals($user, $manager->getCurrentUser());
        $this->assertLocationHeader($initialPath, $response);
    }
}
