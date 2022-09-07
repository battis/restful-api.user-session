<?php

namespace Battis\UserSession\Repositories;

use Battis\UserSession\Entities\UserEntityInterface;

interface UserRepositoryInterface
{
    public function getUserEntityByUsername(
        string $username
    ): ?UserEntityInterface;
}
