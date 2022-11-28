<?php

namespace Battis\UserSession\Tests;

use PHPUnit\Framework\TestCase;

class TestZZZ extends TestCase
{
    public static function tearDownAfterClass(): void
    {
        ob_end_flush();
    }
}
