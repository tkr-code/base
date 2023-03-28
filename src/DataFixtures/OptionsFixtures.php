<?php

namespace App\DataFixtures;

use App\Entity\Options;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OptionsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            [
                'label'=>"Nom de l'application",
                'name'=>'app_name',
                'value'=>'TKR APP',
            ],
            [
                'label'=>"Couleur de la sidebar",
                'name'=>'sidebar_color',
                'value'=>'#343a40',
            ],
            [
                'label'=>"Logo de la sidebar",
                'name'=>'sidebar_logo',
                'value'=>'AdminLTELogo.png',
            ],
        ];
        foreach ($data as $key => $value) {
            $options = new Options;
            $options->setLabel($value['label'])->setName($value['name'])->setValue($value['value']);
            $manager->persist($options);
        }
        $manager->flush();
    }
}
