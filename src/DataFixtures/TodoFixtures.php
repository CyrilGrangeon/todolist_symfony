<?php

namespace App\DataFixtures;

use App\Entity\Todo;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TodoFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i = 0; $i < 20; $i++){
        $todo = new Todo();
        $todo->setTitle('title'.$i);
        $todo->setContent('content'.$i);
        $todo->setIsDone('is_done'.$i);
        $todo->setCreatedAt(new \DateTimeImmutable());
        $todo->setDoneAt(new \DateTimeImmutable());

        $manager->persist($todo);
        $manager->flush();
        }
    }
}
