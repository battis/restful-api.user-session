<?php

use Battis\UserSession\Repositories\UserRepositoryInterface;
use Example\UserRepository;

return [
    UserRepositoryInterface::class => fn() => new UserRepository(),
];
