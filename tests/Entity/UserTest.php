<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testGetTasks()
    {
        $user = new User();

        $user->addTask(new Task());
        $user->addTask(new Task());

        $this->assertCount(2, $user->getTasks());
    }

    public function testRemoveTask()
    {
        $user = new User();
        $task = new Task();
        $task2 = new Task();

        $user->addTask($task);
        $user->addTask($task2);

        $this->assertCount(2, $user->getTasks());

        $user->removeTask($task2);

        $this->assertCount(1, $user->getTasks());

    }
}
