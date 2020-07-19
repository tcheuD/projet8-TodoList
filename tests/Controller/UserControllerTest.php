<?php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends BaseController
{

    /** @dataProvider usersUrls */
    public function testUsersReturn403ForUnauthorizedAccess($url)
    {
        $client = self::$client;
        $client->request('GET', $url, [], [], [
            'PHP_AUTH_USER' => 'user1',
            'PHP_AUTH_PW'   => 'password',
        ]);

        $this->assertSame(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    /** @dataProvider usersUrls */
    public function testUsersReturn200ForAdmin($url)
    {
        $client = self::$client;
        $client->request('GET', $url, [], [], [
            'PHP_AUTH_USER' => 'user0',
            'PHP_AUTH_PW'   => 'password',
        ]);

        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function usersUrls()
    {
        return [
            ['/users'],
            ['/users/create'],
            ['/users/1/edit']
        ];
    }
}
