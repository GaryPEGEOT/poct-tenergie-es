<?php

namespace App\DataFixtures;

use App\Factory\TodoItemFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        TodoItemFactory::createMany(10);
    }
}
