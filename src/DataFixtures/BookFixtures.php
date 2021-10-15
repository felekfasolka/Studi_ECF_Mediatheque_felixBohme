<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\Genre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class BookFixtures extends Fixture

{
    /** @var Generator */
    protected $faker;

    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create();
        // create 10.000 Media Items
        for ($i = 0; $i < 10000; $i++)
        {
            $book = new Book();
            $book->setTitle($this->faker->sentence($nbWords = 6, $variableNbWords = true));
            $book->setCoverPicture('book_cover'.$this->faker->biasedNumberBetween($min = 1, $max = 4, $function = 'sqrt').'.jpg');
            $book->setDateOfPublication($this->faker->dateTimeThisCentury($max = 'now', $timezone = 'Europe/Paris'));
            $book->setDescription($this->faker->text($maxNbChars = 200));
            $book->setAuthor($this->faker->name);

            $manager->persist($book);
        }
        $manager->flush();
    }
}
