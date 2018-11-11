<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        $os    = ['Symbian OS', 'BlackBerry OS', 'Windows Phone', 'Android', 'Bada'];
        $network = [
            'GSM 2G / GPRS 2,5G',
            'GPRS 2,5G',
            'EDGE 2,75G',
            'EDGE 2,75G /3G',
            '3G / 3G+',
            'H+',
            'H+ / 3G+',
            '4G / 4G+',
            '4G+ UHD'
        ];

        for ($i = 0; $i < 134; $i++) {
            $product = (new Product())
                ->setCreated($faker->dateTimeThisYear)
                ->setPrice($faker->randomFloat(2, 80, 999))
                ->setNetwork($network[rand(0, 8)])
                ->setOs($os[rand(0, 4)])
                ->setName($faker->firstNameFemale.' '.$faker->century)
                ->setDescription($faker->text)
                ->setWeight($faker->randomFloat(2, 100, 450));

            $manager->persist($product);
        }

        $manager->flush();
    }
}
