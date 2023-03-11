<?php

namespace Battis\UserSession\Tests\Fixtures\Reusable;

use Battis\UserSession\Entities\UserEntityInterface;

class User implements UserEntityInterface
{
    public $id;
    public string $username;
    public string $password;
    public string $hash;

    public function __construct($id = null, string $password = null)
    {
        $this->id = $id ?? random_int(1, 1000);
        $this->username = md5(time() . "username");
        $this->password = $password ?? md5(time() . "password");
        $this->hash = password_hash($this->password, PASSWORD_DEFAULT);
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
