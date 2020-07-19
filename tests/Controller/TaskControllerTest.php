<?php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends BaseController
{
    public function testTaskCantBeDeletedByNonAuthor()
    {
        $client = self::$client;
        $client->request('GET', '/tasks/16/delete', [], [], [
            'PHP_AUTH_USER' => 'user0',
            'PHP_AUTH_PW'   => 'password',
        ]);

        $this->assertSame(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    public function testTaskWithAnonUserCanBeDeletedByAdmin()
    {
        $client = self::$client;
        $client->request('GET', '/tasks/31/delete', [], [], [
            'PHP_AUTH_USER' => 'user0',
            'PHP_AUTH_PW'   => 'password',
        ]);

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testTaskWithAnonUserCantBeDeletedByUser()
    {
        $client = self::$client;
        $client->request('GET', '/tasks/31/delete', [], [], [
            'PHP_AUTH_USER' => 'user1',
            'PHP_AUTH_PW'   => 'password',
        ]);

        $this->assertSame(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

}
