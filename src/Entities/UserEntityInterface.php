<?php

namespace Battis\UserSession\Entities;

interface UserEntityInterface
{
    public function getIdentifier();
    public function passwordVerify(string $password): bool;
}
