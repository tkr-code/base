<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
   
    private $passwordEncoder;
    private $service;
    public function __construct(EntityManagerInterface $entityManagerInterface, UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->passwordEncoder = $userPasswordHasherInterface;
    }
    public function load(ObjectManager $manager): void
    {
        $admin = [
            ['first_name' => 'Admin','last_name' => 'Pmd','email' => 'admin@pmd-developper.com','roles' => ["ROLE_ADMIN"]],
            // ['first_name' => 'testEmail','last_name' => 'test email','email' => 'test@mail.com','roles' => ["ROLE_CLIENT"]],
        ];
        foreach ($admin as $key => $value) {
            $user = new User();
            $user->setPrenom($value['first_name'])
            ->setNom($value['last_name']);
            $user->setEmail($value['email']);
            // $user->setStatus('Activer');
            $user->setIsVerified(true);
            // $user->setCle($this->service->aleatoire(100));
            // $user->setPhoneNumber('770000000');
            $user->setPassword($this->passwordEncoder->hashPassword($user,'password'))
            ->setRoles($value['roles']);
            $manager->persist($user);
        }
        
        $manager->flush();
    }
}
