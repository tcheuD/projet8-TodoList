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

    public function testCreateUserIfLogged()
    {
        $client = self::$client;
        $crawler = $client->request('GET', '/users/create', [], [], [
            'PHP_AUTH_USER' => 'user0',
            'PHP_AUTH_PW'   => 'password',
        ]);

        $form = $crawler->selectButton('Ajouter')->form(
            [
                'user[username]'         => 'user'.mt_rand(),
                'user[password][first]'  => 'password',
                'user[password][second]' => 'password',
                'user[email]'            => 'user'.mt_rand().'@mail.com',
                'user[roles]'            => 'ROLE_USER',
            ]
        );

        $client->submit($form);
        $this->assertSame(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }

    public function testEditUserIfLogged()
    {
        $client = self::$client;
        $crawler = $client->request('GET', '/users/4/edit', [], [], [
            'PHP_AUTH_USER' => 'user0',
            'PHP_AUTH_PW'   => 'password',
        ]);

        $form = $crawler->selectButton('Modifier')->form(
            [
                'user[password][first]'  => 'password',
                'user[password][second]' => 'password',
                'user[roles]'            => 'ROLE_ADMIN',
            ]
        );

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
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
