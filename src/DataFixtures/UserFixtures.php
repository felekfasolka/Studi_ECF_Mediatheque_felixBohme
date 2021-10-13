<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
         $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {

        // create 20 Users
        for ($i = 0; $i < 25; $i++)
        {
            $user = new User();
            $user->setEmail('user'.$i.'@mail.com');
            $user->setFirstname('User'.$i);
            $user->setSurname('Mediatheque');
            $user->setAdress('Street No '.$i);
            $user->setBirthdate(new \DateTime("1980-12-$i"));
            $user->setRoles(array('ROLE_USER'));
            $user->setIsEnabled('0');
            $user->setPassword($this->passwordHasher->hashPassword(
                         $user,
                         'user'.$i
                     ));
            $manager->persist($user);
        }
        $manager->flush();
    }
}
