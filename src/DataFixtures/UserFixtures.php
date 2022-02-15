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
                'first_name' => 'Malick',
                'last_name' => 'Tounkara',
                'email' => 'admin1@mail.com',
                'roles' => ["ROLE_ADMIN"]
        ],
        [
                'first_name' => 'Mamadou','last_name' => 'Dieme','email' => 'admin2@mail.com',
                'roles' => ["ROLE_ADMIN"]
        ],
        [
                'first_name' => 'Pepin','last_name' => 'Ngoulou','email' => 'admin3@mail.com',
                'roles' => ["ROLE_ADMIN"]],
        [
                'first_name' => 'Pepin2','last_name' => 'Ngoulou','email' => 'editor1@mail.com',
                'roles' => ["ROLE_EDITOR"]],
        [
                'first_name' => 'prenom1','last_name' => 'nom1','email' => 'user1@mail.com',
                'roles' => ["ROLE_USER"]],
        [
                'first_name' => 'prenom2','last_name' => 'nom2','email' => 'user2@mail.com',
                'roles' => ["ROLE_USER"]],
        [
                'first_name' => 'prenom3','last_name' => 'nom3','email' => 'user3@mail.com',
                'roles' => ["ROLE_USER"]],
        [
                'first_name' => 'prenom4','last_name' => 'nom4','email' => 'user4@mail.com',
                'roles' => ["ROLE_USER"]],
        [
                'first_name' => 'prenom5','last_name' => 'nom5','email' => 'user5@mail.com',
                'roles' => ["ROLE_USER"]],
        ];
        foreach ($users as $value) {
            $user = new User();
            $personne = new Personne();
            $personne->setFirstName($value['first_name'])
            ->setLastName($value['last_name']);
            $user->setEmail($value['email']);
            $user->setIsVerified(true);
            $user->setStatus('offline');
            $user->setPassword($this->passwordEncoder->hashPassword($user,'password'))
            ->setRoles($value['roles'])
            ->setPersonne($personne);
            $this->addReference('_user_'.$value['first_name'],$user);
            $this->em->persist($user);
        }
        $this->em->flush();
    }
}