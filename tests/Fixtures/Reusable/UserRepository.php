<?php

namespace Battis\UserSession\Tests\Fixtures\Reusable;

use Battis\UserSession\Entities\UserEntityInterface;
use Battis\UserSession\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    private array $users = [];

    public function addUser(User $user)
    {
        $this->users[$user->username] = $user;
    }

    public function getUserEntityByUsername(string $username): ?UserEntityInterface
    {
        return $this->users[$username] ?? null;
    }
}
