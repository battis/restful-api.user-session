<?php

namespace Example;

use Battis\UserSession\Entities\UserEntityInterface;
use Battis\UserSession\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
  private $users = [];

  public function __construct()
  {
    foreach (
      [
        "admin" => "password",
        "user" => "123",
      ]
      as $username => $password
    ) {
      $this->users[$username] = new UserEntity($username, $password);
    }
  }

  public function getUserEntityByUsername(
    string $username
  ): ?UserEntityInterface {
    if (key_exists($username, $this->users)) {
      return $this->users[$username];
    }
    return null;
  }
}
