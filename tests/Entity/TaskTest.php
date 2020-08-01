<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testGetCreatedAt()
    {
        $task = new Task();
        $dateTime = new \DateTime();

        $task->setCreatedAt($dateTime);

        $this->assertSame($dateTime, $task->getCreatedAt());
    }
}
