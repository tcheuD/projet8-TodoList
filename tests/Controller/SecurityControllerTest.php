<?php

namespace App\Tests\Controller;

class SecurityControllerTest extends BaseController
{

    public function testLoginCheck()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form();
        $client->submit($form);

        $crawler = $client->followRedirect();
        $this->assertSame(1, $crawler->count());
    }

    public function testLogout()
    {
        $client = static::createClient();

        $client->request('GET', '/logout');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $client->followRedirect();
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}
