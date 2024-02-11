<?php

namespace App\DataFixtures;

use App\Entity\Adresse;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AdresseFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i=1; $i <2 ; $i++) { 
            # code...
            $adresse = new Adresse();
            $adresse
                ->setCity('Dakar')
                ->setRue('Sacre coeur 2')
                ->setTel('781278288')
                ->setPays('Senegal')
                ->setCodePostal('11000')
            ;
            $this->addReference('adresse_'.$i,$adresse);
            $manager->persist($adresse);
        }
        $manager->flush();
    }
}
