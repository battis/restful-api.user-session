<?php

namespace Example;

use Battis\UserSession\Entities\UserEntityInterface;

class UserEntity implements UserEntityInterface
{
  private $username;
  private $hash;

  public function __construct($username, $password)
  {
    $this->username = $username;
    $this->hash = password_hash($password, PASSWORD_DEFAULT);
  }

  public function getIdentifier(): string
  {
    return $this->username;
  }

  public function passwordVerify(string $password): bool
  {
    return password_verify($password, $this->hash);
  }
}
