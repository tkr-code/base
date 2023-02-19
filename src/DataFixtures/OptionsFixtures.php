<?php

namespace App\DataFixtures;

use App\Entity\Options;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OptionsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $options = new Options;
        $options->setName('app_name')->setValue('TKR APP');
        $manager->persist($options);

        $manager->flush();
    }
}
