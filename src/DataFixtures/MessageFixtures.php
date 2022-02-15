<?php

namespace App\DataFixtures;

use App\Entity\Message;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MessageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {

            $message = new Message();
            $message->setEmetteur($this->getReference('_user_Malick'));
            $message->setRecepteur($this->getReference('_user_Mamadou'));
            $message->setMessage('Salut mamadou.');
            $message->setIsRead(false);
            $manager->persist($message);
        
            $message = new Message();
            $message->setEmetteur($this->getReference('_user_Mamadou'));
            $message->setRecepteur($this->getReference('_user_Malick'));
            $message->setMessage('Salut Malick.');
            $message->setIsRead(false);
            $manager->persist($message);

        $manager->flush();
    }

    public function getDependencies()
    {
        return[
            UserFixtures::class
        ];
    }
}
