<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class BaseController extends WebTestCase
{
    protected static ?KernelBrowser $client = null;

    protected function setUp(): void
    {
        if (null === self::$client) {
            self::$client = static::createClient();
        }
    }

    protected function login($username, $password)
    {
        return static::createClient([], [
            'PHP_AUTH_USER' => $username,
            'PHP_AUTH_PW'   => $password,
        ]);
    }
}
