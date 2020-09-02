<?php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends BaseController
{
    public function testTaskCantBeDeletedByNonAuthor(): void
    {
        $client = self::$client;
        $client->request('GET', '/tasks/26/delete', [], [], [
            'PHP_AUTH_USER' => 'user1',
            'PHP_AUTH_PW'   => 'password',
        ]);

        self::assertSame(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    public function testTaskWithAnonUserCanBeDeletedByAdmin(): void
    {
        $client = self::$client;
        $client->request('GET', '/tasks/31/delete', [], [], [
            'PHP_AUTH_USER' => 'user0',
            'PHP_AUTH_PW'   => 'password',
        ]);

        self::assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testTaskWithAnonUserCantBeDeletedByUser(): void
    {
        $client = self::$client;
        $client->request('GET', '/tasks/31/delete', [], [], [
            'PHP_AUTH_USER' => 'user1',
            'PHP_AUTH_PW'   => 'password',
        ]);

        self::assertSame(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    public function testCreateAction(): void
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
        self::assertSame(
            302,
            $client->getResponse()->getStatusCode()
        );

        $crawler = $client->followRedirect();
        self::assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }

    public function testTaskListActionIfNotLogged(): void
    {
        $client = static::createClient();
        $client->request('GET', '/tasks');

        self::assertSame(302, $client->getResponse()->getStatusCode());
    }

    public function testEditTaskIfLoggedAndAdmin(): void
    {
        $client = self::$client;
        $crawler = $client->request('GET', '/tasks/1/edit', [], [], [
            'PHP_AUTH_USER' => 'user0',
            'PHP_AUTH_PW'   => 'password',
        ]);
        self::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Modifier')->form(
            [
                'task[title]' => 'New title for the Task',
            ]
        );
        $client->submit($form);

        self::assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        self::assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }

    public function testToogleTaskActionIfLoggedAndAdmin(): void
    {
        $client = self::$client;
        $client->request('GET', '/tasks/1/toggle', [], [], [
            'PHP_AUTH_USER' => 'user0',
            'PHP_AUTH_PW'   => 'password',
        ]);

        self::assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        self::assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }
}

