<?php

namespace Battis\UserSession\Tests\Fixtures\ManagerTest;

use SlimSession\Helper;

class Session extends Helper
{
    public function __construct()
    {
        global $_SESSION;
        if (php_sapi_name() === 'cli' && !isset($_SESSION)) {
            $_SESSION = [];
        }
    }

    public static function destroy()
    {
        global $_SESSION;
        if (php_sapi_name() === 'cli') {
            $_SESSION = [];
        }
        parent::destroy();
    }
}
