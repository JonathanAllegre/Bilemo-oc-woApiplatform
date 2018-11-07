<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;

class CustomerFixtures extends Fixture
{
    private $encoder;
    private $manager;

    const CUSTOMER_ONE_REFERENCE   = "Client 1";
    const CUSTOMER_TWO_REFERENCE   = "Client 2";
    const CUSTOMER_THREE_REFERENCE = "Client 3";
    const CUSTOMER_FOUR_REFERENCE  = "Client 4";

    public function __construct(UserPasswordEncoderInterface $encoder, ObjectManager $manager)
    {
        $this->encoder = $encoder;
        $this->manager = $manager;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        $customer1 = $this->newCustomer($faker->company, 'admin');
        $this->addReference(self::CUSTOMER_ONE_REFERENCE, $customer1);

        $customer2 = $this->newCustomer($faker->company, 'admin');
        $this->addReference(self::CUSTOMER_TWO_REFERENCE, $customer2);

        $customer3 = $this->newCustomer($faker->company, 'admin');
        $this->addReference(self::CUSTOMER_THREE_REFERENCE, $customer3);

        $customer4 = $this->newCustomer($faker->company, 'admin');
        $this->addReference(self::CUSTOMER_FOUR_REFERENCE, $customer4);

        $manager->flush();
    }

    public function newCustomer(string $name, string $password)
    {
        $customer = new Customer();
        $customer->setName($name);
        $customer->setPassword($this->encoder->encodePassword($customer, $password));

        $this->manager->persist($customer);

        return $customer;
    }
}
