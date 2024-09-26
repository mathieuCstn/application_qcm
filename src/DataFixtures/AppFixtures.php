<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher
    ){}

    public function load(ObjectManager $manager): void
    {
        $user = new User;
        $user
            ->setFirstname('Mathieu')
            ->setLastname('Constantin')
            ->setEmail('mathieuconstantin01@gmail.com')
            ->setPassword($this->userPasswordHasher->hashPassword($user, 'chevre'))
            ->setRoles(['ROLE_ADMIN'])
        ;
        $manager->persist($user);

        $manager->flush();
    }
}
