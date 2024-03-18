# Battis\UserSession

[![Latest Version](https://img.shields.io/packagist/v/battis/user-session.svg)](https://packagist.org/packages/battis/user-session)
[![codecov](https://codecov.io/gh/battis/user-session/branch/main/graph/badge.svg)](https://codecov.io/gh/battis/user-session)

User session management for Slim Framework

## Installation

```bash
composer install battis/user-session
```

## Use

See [example](https://github.com/battis/restful-api/tree/main/examples/user-session) for sample implementation. The highlights are:

### Add `UserSession\Dependencies` definitions

Use `UserSession\Dependencies` to prepare container with dependency definitions (this should be done _before_ any additional app-specific definitions wherein you might want to override any of the UserSession defaults):

```php
/** @var DI\ContainerBuilder $containerBuilder */
$containerBuilder->addDefinitions(
  Battis\UserSession\Dependencies::definitions()
);
```

### Implement `UserEntityInterface` & `UserRepositoryInterface`

Define implementations of `UserEntityInterface` and `UserRepositoryInterface` and

```php
namespace Example;

class UserEntity implements Battis\UserSession\Entities\UserEntityInterface
{
  public function getIdentifier(): string
  {
    // ...
  }

  public function passwordVerify(string $password): bool
  {
    // ...
  }
}
```

```php
<?php

namespace Example;

class UserRepository implements Battis\UserSession\Repositories\UserRepositoryInterface
{
  public function getUserEntityByUsername(
    // ...
  }
}
```

Define these implementations (or, at least, your `UserRepositoryInterface` implementation) in the container:

```php
/** @var DI\ContainerBuilder $containerBuilder */
$containerBuilder->addDefinitions([
  Battis\UserSession\Repositories\UserRepositoryInterface::class => fn() => new Example\UserRepository(),
]);
```

### Define `/auth` endpoints

Use `UserSession\Controller` to define authentication endpoints (`/auth/login` and `/auth/logout`):

```php
/** @var Slim\App $app */
$app->group(
  Battis\UserSession\Controller::ENDPOINT,
  Battis\UserSession\Controller::class
);
```

### Use `Session` or `RequireAuthentication` middleware

Add a user session that provides access to the currently logged-in user to an endpoint (or group) by adding the `UserSession\Middleware\Session` middleware:

```php
/** @var Slim\App $app */
$app
  ->get('/home', Example\PageRenderer::class)
  ->add(Battis\UserSession\Middleware\Session::class);
```

Restrict access to an endpoint (or group) to authenticated users by adding the `UserSession\Middleware\RequireAuthentication` middleware:

```php
/** @var Slim\App $app */
$app
  ->get('/protected', Example\PageRenderer::class)
  ->add(Battis\UserSession\Middleware\RequireAuthentication::class);
```
