<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 30/11/2018
 * Time: 07:13
 */

namespace App\Tests\User;


use App\Entity\Customer;
use App\Service\UserService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserServiceTest extends KernelTestCase
{

    public function testGetUserList()
    {

        $objectManager = $this->getContainer()->get(ObjectManager::class);

        $userService = new UserService($objectManager);

        //DATA
        $params = $this->getParamsForTestGetUserList();
        $customer = $objectManager->getRepository(Customer::class)->findOneByName('Martin');

        $result = $userService->getUserList($params, $customer);

        dd($result);

    }

    // PARAMS
    public function getParamsForTestGetUserList()
    {
        return [
            "limit" => "30",
            "order" => "desc",
            "page" => "1",
        ];
    }

    // CONTAINER
    protected function getContainer()
    {
        self::bootKernel();
        // returns the real and unchanged service container
        $container = self::$kernel->getContainer();
        // gets the special container that allows fetching private services
        $container = self::$container;

        return $container;
    }

}