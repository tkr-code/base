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
            $message->setEmetteur($this->getReference('_user_Admin'));
            $message->setRecepteur($this->getReference('_user_User'));
            $message->setMessage('Salut User.');
            $message->setIsRead(false);
            $manager->persist($message);
        
            $message = new Message();
            $message->setEmetteur($this->getReference('_user_User'));
            $message->setRecepteur($this->getReference('_user_Admin'));
            $message->setMessage('Salut Admin.');
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
