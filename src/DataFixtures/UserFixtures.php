<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    /** @var Generator */
    protected $faker;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
         $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create();

        // create 20 Users
        for ($i = 0; $i < 25; $i++)
        {
            $user = new User();
            $user->setEmail('user'.$i.'@mail.com');
            $user->setFirstname($this->faker->firstName);
            $user->setSurname($this->faker->lastName);
            $user->setAdress($this->faker->address);
            $user->setBirthdate($this->faker->dateTimeBetween($startDate = '-70 years', $endDate = '-18 years', $timezone = 'Europe/Paris'));
            $user->setRoles(array('ROLE_USER'));
            $user->setIsEnabled('0');
            $user->setPassword($this->passwordHasher->hashPassword(
                         $user,
                         'user'.$i.'password'
                     ));
            $manager->persist($user);
        }
        $manager->flush();
    }
}
