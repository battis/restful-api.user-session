<?php

use Battis\UserSession\Repositories\UserRepositoryInterface;
use Battis\UserSession\Tests\Fixtures\Reusable\UserRepository;

return [
    UserRepositoryInterface::class => fn() => new UserRepository(),
];
