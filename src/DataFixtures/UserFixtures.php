<?php

namespace App\DataFixtures;

use App\Entity\Personne;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $em;
    private $passwordEncoder;
    public function __construct(EntityManagerInterface $entityManagerInterface, UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->em = $entityManagerInterface;
        $this->passwordEncoder = $userPasswordHasherInterface;
    }
    public function load(ObjectManager $manager)
    {
        $users = [
        [
                'first_name' => 'Admin',
                'last_name' => 'Admin',
                'email' => 'admin@mail.com',
                'roles' => ["ROLE_ADMIN"]
        ],
        [
                'first_name' => 'Editor',
                'last_name' => 'Editor',
                'email' => 'editor@mail.com',
                'roles' => ["ROLE_EDITOR"]
        ],
        [
                'first_name' => 'User',
                'last_name' => 'User',
                'email' => 'user@mail.com',
                'roles' => ["ROLE_USER"]
        ],
        ];
        foreach ($users as $value) {
            $user = new User();
            $user->setFirstName('Prenom_'.$value['first_name'])
            ->setLastName('Nom_'.$value['last_name']);
            $user->setEmail($value['email']);
            $user->setIsVerified(true);
            $user->setStatus('Offline');
            $user->setPassword($this->passwordEncoder->hashPassword($user,'password'))
            ->setRoles($value['roles']);
            $this->addReference('_user_'.$value['first_name'],$user);
            $this->em->persist($user);
        }
        $this->em->flush();
    }
}