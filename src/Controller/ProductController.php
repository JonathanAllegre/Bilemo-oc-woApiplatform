<?php

namespace App\Controller;

use App\Entity\Product;
use App\Representation\Products;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    /**
     * @Rest\Get(
     *     path = "/api/products/{id}",
     *     name = "app_product_show",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View()
     */
    public function showAction(Product $product)
    {
        return $product;
    }

    /**
     * @Rest\Get(
     *     path = "/api/products",
     *     name = "app_product_list",
     * )
     * @Rest\View()
     */
    public function listAction()
    {
        $pager = $this
            ->getDoctrine()
            ->getRepository(Product::class)
            ->search();

        //$products = $this->getDoctrine()->getRepository(Product::class)->findAll();

        return new Products($pager);
        //return $pager->getCurrentPageResults();

        //TODO: pagination & representation
    }
}
