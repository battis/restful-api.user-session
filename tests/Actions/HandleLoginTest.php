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
        $app = $this->getAppInstance();

        $user = new User();

        /** @var UserRepository $userRepo */
        $userRepo = $app->getContainer()->get(UserRepositoryInterface::class);
        $userRepo->addUser($user);

        /** #var Manager $manager */
        $manager = $app->getContainer()->get(Manager::class);

        $response = $app->handle($this->createRequest('POST', Manager::DEFAULT_LOGIN_PATH));
    }
}
