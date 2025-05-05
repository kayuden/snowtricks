<?php

namespace App\DataFixtures;

use App\Factory\TrickFactory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void //symfony console doctrine:fixtures:load
    {
        TrickFactory::createMany(10);
    }
}
