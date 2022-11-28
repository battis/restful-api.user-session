<?php

namespace Battis\UserSession\Tests\Actions;

use Battis\UserSession\Manager;
use Battis\UserSession\Tests\Fixtures\Reusable\User;
use Battis\UserSession\Tests\TestCase;
use SimpleXMLElement;

class ShowLoginViewTest extends TestCase
{
    public function testLoginView()
    {
        $app = $this->getAppInstance();
        $response = $app->handle($this->createRequest('GET', Manager::DEFAULT_LOGIN_PATH));
        $payload = (string) $response->getBody();
        $this->assertNotEmpty($payload);
        $xml = new SimpleXMLElement($payload);
        $this->assertCount(0, $xml->{'non-document-error'});
        $this->assertCount(0, $xml->error);
    }

    public function testLoginViewWithActiveUser()
    {
        $app = $this->getAppInstance();

        /** @var Manager */
        $manager = $app->getContainer()->get(Manager::class);
        $manager->startUserSession(new User());

        $response = $app->handle($this->createRequest('GET', Manager::DEFAULT_LOGIN_PATH));
        $this->assertLocationHeader(Manager::DEFAULT_REDIRECT, $response);
    }
}
