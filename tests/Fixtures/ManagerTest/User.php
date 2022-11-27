<?php

namespace Battis\UserSession\Tests\Fixtures\ManagerTest;

use Battis\UserSession\Entities\UserEntityInterface;

class User implements UserEntityInterface
{
    public $id;
    public $password;
    public $hash;

    public function __construct($id, $password)
    {
        $this->id = $id;
        $this->password = $password;
        $this->hash = password_hash($password, PASSWORD_DEFAULT);
    }

    public function getIdentifier()
    {
        return $this->id;
    }

    public function passwordVerify(string $password): bool
    {
        return password_verify($password, $this->hash);
    }
}
