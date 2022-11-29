<?php

namespace Battis\UserSession\Tests;

use Battis\UserSession\Manager;
use Battis\UserSession\Tests\Fixtures\Reusable\User;
use ReflectionClass;

class ManagerTest extends TestCase
{
    private static $USER;
    private static $REDIRECT;

    public static function setUpBeforeClass(): void
    {
        TestCase::setUpBeforeClass();
        $reflection = new ReflectionClass(Manager::class);
        self::$USER = $reflection->getConstant('USER');
        self::$REDIRECT = $reflection->getConstant('REDIRECT');
    }

    private function getManager(...$args): Manager
    {
        return new Manager($this->getSession(), ...$args);
    }

    public function testConstructorDefault()
    {
        $session = $this->getSession();
        $manager = $this->getManager();
        $this->assertFalse($manager->sessionIsActive());
        $this->assertNull($manager->getCurrentUser());

        $this->assertFalse($session->__isset(self::$USER));
        $this->assertFalse($session->__isset(self::$REDIRECT));
    }

    public function testConstructorCustomLoginPath()
    {
        $login = 'custom-login-path';
        $manager = $this->getManager($login);
        $response = $manager->startUserLogin($this->createRequest('GET', '/'));
        $this->assertLocationHeader($login, $response);
    }

    public function testConstructorCustomDefaultRedirect()
    {
        $redirect = 'custom-default-redirect';
        $manager = $this->getManager('', $redirect);
        $response = $manager->startUserSession(new User());
        $this->assertLocationHeader($redirect, $response);

        $response = $manager->startUserLogin($this->createRequest('GET', $manager::DEFAULT_LOGIN_PATH));
        $this->assertLocationHeader($redirect, $response);
    }

    public function testStartUserLogin()
    {
        $path = 'requested-path';
        $session = $this->getSession();
        $manager = $this->getManager();
        $request = $this->createRequest('GET', $path);
        $response = $manager->startUserLogin($request);
        $this->assertLocationHeader(Manager::DEFAULT_LOGIN_PATH, $response);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertFalse($session->__isset(self::$USER));
        $this->assertEquals($path, $session->get(self::$REDIRECT));
    }

    public function testStartUserLoginWithLoggedInUser()
    {
        $session = $this->getSession();
        $manager = $this->getManager();
        $user = new User();
        $request = $this->createRequest('GET', $manager::DEFAULT_LOGIN_PATH);
        $session->set(self::$USER, $user);
        $this->assertTrue($manager->sessionIsActive());
        $this->assertEquals($user, $manager->getCurrentUser());

        $response = $manager->startUserLogin($request);
        $this->assertTrue($manager->sessionIsActive());
        $this->assertEquals($user, $manager->getCurrentUser());
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertLocationHeader($manager::DEFAULT_REDIRECT, $response);
    }

    public function testStartUserSession()
    {
        $user = new User();
        $manager = $this->getManager();

        $response = $manager->startUserSession($user);
        $this->assertLocationHeader(Manager::DEFAULT_REDIRECT, $response);
        $this->assertTrue($manager->sessionIsActive());
        $this->assertEquals($user, $manager->getCurrentUser());
    }

    public function testEndUserSession()
    {
        $session = $this->getSession();
        $manager = $this->getManager();
        $user = new User();

        $manager->startUserSession($user);
        $this->assertTrue($manager->sessionIsActive());
        $this->assertEquals($user, $manager->getCurrentUser());

        $manager->endUserSession();
        $this->assertEquals(0, $session->count());
        $this->assertFalse($manager->sessionIsActive());
        $this->assertNull($manager->getCurrentUser());
    }
}
