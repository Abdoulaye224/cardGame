<?php

namespace App\DataFixtures;

use App\Entity\Card;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;

class CardFixture extends Fixture
{
    private $categoryRepository;
    private $userRepository;

    public function __construct(CategoryRepository $categoryRepository, UserRepository $userRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Faker::create();

        $categoryList= $this->categoryRepository->findAll();

        for ($i = 0; $i < 20; $i++) {
            $card = new Card();

            $categoryIndex = $faker->numberBetween(0, count($categoryList)-1);

            $card->setTitle($faker->title);
            $card->setAttack($faker->numberBetween(0, 100));
            $card->setLifePoint($faker->numberBetween(0, 100));
            $card->setCategory($categoryList[$categoryIndex]);

            $manager->persist($card);
        }

        $manager->flush();
    }
}
