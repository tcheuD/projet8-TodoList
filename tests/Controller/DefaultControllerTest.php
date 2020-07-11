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
}
