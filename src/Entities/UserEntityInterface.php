<?php

namespace Battis\UserSession\Entities;

interface UserEntityInterface
{
  public function getIdentifier(): string;
  public function passwordVerify(string $password): bool;
}
