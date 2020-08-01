<?php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;

class DefaultControllerTest extends BaseController
{
    public function testIndex()
    {
        $client = self::$client;
        $client->request('GET', '/login');
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testIndexWillRedirectToLogin()
    {
        $client = self::$client;
        $client->request('GET', '/', [], [], [
            'PHP_AUTH_USER' => 'user1',
            'PHP_AUTH_PW'   => 'password',
        ]);
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
}
