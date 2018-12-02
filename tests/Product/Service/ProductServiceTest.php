<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 22/11/2018
 * Time: 07:09
 */

namespace App\Tests\Product\Service;

use App\Repository\ProductRepository;
use App\Service\ProductService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductServiceTest extends KernelTestCase
{

    public function testShowProductList()
    {

        $productRepository = $this->getContainer()->get(ProductRepository::class);
        $manager           = $this->getContainer()->get(ObjectManager::class);


        $productService = new ProductService($productRepository, $manager);

        $result = $productService->showProductList($this->getDataForTestShowProductList());

        dd($result);
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

    // GET DATA
    protected function getDataForTestShowProductList()
    {
        return [
            'limit' => 1,
            'order' => "desc",
            'page'  => 1,
        ];
    }
}
