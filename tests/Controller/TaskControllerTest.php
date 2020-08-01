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

    public function testCreateAction()
    {
        $client = self::$client;
        $crawler = $client->request('GET', '/tasks/create', [], [], [
            'PHP_AUTH_USER' => 'user0',
            'PHP_AUTH_PW'   => 'password',
        ]);

        $form = $crawler->selectButton('Ajouter')->form(
            [
                'task[title]'   => 'New title',
                'task[content]' => 'New content',
            ]
        );

        $client->submit($form);
        $this->assertSame(
            302,
            $client->getResponse()->getStatusCode()
        );

        $crawler = $client->followRedirect();
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }

    public function testTaskListActionIfNotLogged()
    {
        $client = static::createClient();
        $client->request('GET', '/tasks');

        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }

    public function testEditTaskIfLoggedAndAdmin()
    {
        $client = self::$client;
        $crawler = $client->request('GET', '/tasks/1/edit', [], [], [
            'PHP_AUTH_USER' => 'user0',
            'PHP_AUTH_PW'   => 'password',
        ]);
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Modifier')->form(
            [
                'task[title]' => 'New title for the Task',
            ]
        );
        $client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }

    public function testToogleTaskActionIfLoggedAndAdmin()
    {
        $client = self::$client;
        $crawler = $client->request('GET', '/tasks/1/toggle', [], [], [
            'PHP_AUTH_USER' => 'user0',
            'PHP_AUTH_PW'   => 'password',
        ]);

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }
}

