<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');


        for ($i = 0; $i < 10; $i++) {
            $this->newUser(
                $faker->firstName,
                $faker->lastName,
                $faker->email,
                $this->getReference(CustomerFixtures::CUSTOMER_ONE_REFERENCE),
                $faker->password
            );
        }

        for ($i = 0; $i < 14; $i++) {
            $this->newUser(
                $faker->firstName,
                $faker->lastName,
                $faker->email,
                $this->getReference(CustomerFixtures::CUSTOMER_TWO_REFERENCE),
                $faker->password
            );
        }

        for ($i = 0; $i < 34; $i++) {
            $this->newUser(
                $faker->firstName,
                $faker->lastName,
                $faker->email,
                $this->getReference(CustomerFixtures::CUSTOMER_THREE_REFERENCE),
                $faker->password
            );
        }

        for ($i = 0; $i < 6; $i++) {
            $this->newUser(
                $faker->firstName,
                $faker->lastName,
                $faker->email,
                $this->getReference(CustomerFixtures::CUSTOMER_FOUR_REFERENCE),
                $faker->password
            );
        }

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            CustomerFixtures::class,
        ];
    }

    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param Customer $customer
     * @param string $password
     * @return User
     */
    public function newUser(string $firstName, string $lastName, string $email, Customer $customer, string $password)
    {
        $user = new User();
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setEmail($email);
        $user->setCustomer($customer);
        $user->setPassword($password);

        $this->manager->persist($user);

        return $user;
    }
}
