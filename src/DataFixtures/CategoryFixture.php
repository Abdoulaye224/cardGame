<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Repository\CardRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;

class CategoryFixture extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            $category = new Category();

            $category->setName($faker->name);
            $manager->persist($category);

        }

        $manager->flush();
    }
}
