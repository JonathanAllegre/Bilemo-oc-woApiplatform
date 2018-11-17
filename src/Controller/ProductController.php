<?php

namespace App\Controller;

use App\Entity\Product;
use App\Representation\Products;
use FOS\RestBundle\Controller\Annotations as Rest;
use Hateoas\Hateoas;
use Hateoas\HateoasBuilder;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation;
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

        $results = $pager->getCurrentPageResults();

        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

        $paginatedCollection = new PaginatedRepresentation(
            new CollectionRepresentation(
                $pager->getCurrentPageResults(),
                'products', // embedded rel
                'products'  // xml element name
            ),
            'app_product_list', // route
            array(), // route parameters
            $pager->getCurrentPage(),       // page number
            $pager->getMaxPerPage(),      // limit
            $pager->getNbPages(),       // total pages
            'page',  // page route parameter name, optional, defaults to 'page'
            'limit', // limit route parameter name, optional, defaults to 'limit'
            true,   // generate relative URIs, optional, defaults to `false`
            $pager->getNbResults()      // total collection size, optional, defaults to `null`
        );

        $json = $this->get('serializer')->serialize($paginatedCollection, 'json');
        //$json = $hateoas->serialize($paginatedCollection, 'json');
        //$xml  = $hateoas->serialize($paginatedCollection, 'xml');


        return $paginatedCollection;

        //return new Products($pager);
        //return $pager->getCurrentPageResults();

        //TODO: pagination & representation
    }
}
