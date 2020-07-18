<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TaskFixtures extends BaseFixtures implements DependentFixtureInterface
{

    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(50, 'client', function($i) {

            $task = new Task();
            $task->setContent($this->faker->text($maxNbChars = 200));
            $task->setCreatedAt(
                $this->faker->dateTimeBetween('-100 days', '-1 days')
            );
            $task->setTitle('todo'.$i);

            return $task;
        });

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
