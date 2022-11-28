<?php

namespace Battis\UserSession\Tests;

use Battis\UserSession\Manager;
use Battis\UserSession\Tests\Fixtures\Reusable\Session;
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
        return new Manager(new Session(), ...$args);
    }

    public function testConstructorDefault()
    {
        global $_SESSION;
        $manager = $this->getManager();
        $this->assertFalse($manager->sessionIsActive());
        $this->assertNull($manager->getCurrentUser());

        $this->assertFalse(isset($_SESSION[self::$USER]));
        $this->assertFalse(isset($_SESSION[self::$REDIRECT]));
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
    }

    public function testStartUserLogin()
    {
        global $_SESSION;
        $path = 'requested-path';
        $manager = $this->getManager();
        $request = $this->createRequest('GET', $path);
        $response = $manager->startUserLogin($request);
        $this->assertLocationHeader(Manager::DEFAULT_LOGIN_PATH, $response);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertFalse(isset($_SESSION[self::$USER]));
        $this->assertEquals($path, $_SESSION[self::$REDIRECT]);
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
        global $_SESSION;
        $user = new User();
        $manager = $this->getManager();

        $manager->startUserSession($user);
        $this->assertTrue($manager->sessionIsActive());
        $this->assertEquals($user, $manager->getCurrentUser());

        $manager->endUserSession();
        $this->assertEmpty($_SESSION);
        $this->assertFalse($manager->sessionIsActive());
        $this->assertNull($manager->getCurrentUser());
    }
}
