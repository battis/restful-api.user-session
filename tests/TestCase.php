<?php

namespace Battis\UserSession\Tests;

use Battis\DataUtilities\PHPUnit\FixturePath;
use Battis\UserSession\Dependencies;
use Battis\UserSession\Tests\Fixtures\Reusable\Session;
use DI\ContainerBuilder;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Http\ServerRequest;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request;
use Slim\Psr7\Uri;
use SlimSession\Helper;

abstract class TestCase extends PHPUnitTestCase
{
    use ProphecyTrait, FixturePath;

    protected function setUp(): void
    {
        Session::destroy();
    }

    protected function getAppInstance(): App
    {
        $container = (new ContainerBuilder())
            ->addDefinitions(Dependencies::definitions())
            ->addDefinitions(include __DIR__ . '/Fixtures/app/settings.inc.php')
            ->addDefinitions(include __DIR__ . '/Fixtures/app/dependencies.inc.php')
            ->addDefinitions([Helper::class => fn() => new Session()])
            ->build();

        $app = AppFactory::createFromContainer($container);

        include __DIR__ . "/Fixtures/app/middleware.inc.php";
        include __DIR__ . "/Fixtures/app/routes.inc.php";

        return $app;
    }

    protected function createRequest(
        string $method,
        string $path,
        array $headers = ['HTTP_ACCEPT' => 'application/json'],
        ?array $body = null,
        array $cookies = [],
        array $serverParams = []
    ): ServerRequestInterface
    {
        $uri = new Uri('', '', 80, $path);

        $streamFactory = new StreamFactory();
        if ($body !== null) {
            $stream = $streamFactory->createStream(http_build_query($body));
            $headers['Content-Type'] = 'application/x-www-form-urlencoded';
        } else {
            $handle = fopen('php://temp', 'w+');
            $stream = $streamFactory->createStreamFromResource($handle);
        }
        $h = new Headers();
        foreach ($headers as $name => $value) {
            $h->addHeader($name, $value);
        }
        return new ServerRequest(new Request($method, $uri, $h, $cookies, $serverParams, $stream));
    }

    protected function assertLocationHeader($expectedLocation, ResponseInterface $response, bool $exact = true)
    {
        $headers = $response->getHeaders();
        if ($exact) {
            $this->assertContainsEquals('Location', array_keys($headers));
            $this->assertContainsEquals($expectedLocation, $headers['Location']);
        } else {
            $this->assertContains('Location', array_keys($headers));
            $this->assertContains($expectedLocation, $headers['Location']);
        }
    }

}
