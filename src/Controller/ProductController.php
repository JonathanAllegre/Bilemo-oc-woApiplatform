<?php

namespace App\Controller;

use App\Entity\Product;
use App\Representation\Products;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
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
     *
     * @Rest\QueryParam(
     *     name="order",
     *     requirements="asc|desc",
     *     default="desc",
     *     description="Sort Order (asc or desc)"
     * )
     *
     * @Rest\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="30",
     *     description="Max number of products per page"
     * )
     *
     *
     * @Rest\QueryParam(
     *     name="page",
     *     requirements="\d+",
     *     default="1",
     *     description="The page number"
     * )
     *
     * @Rest\View()
     *
     */
    public function listAction(ParamFetcherInterface $paramFetcher)
    {
        $pager = $this
            ->getDoctrine()
            ->getRepository(Product::class)
            ->search(
                $paramFetcher->get('limit'),
                $paramFetcher->get('order'),
                $paramFetcher->get('page')
            );

        $paginatedCollection = new PaginatedRepresentation(
            new CollectionRepresentation(
                $pager->getCurrentPageResults(),
                'products', // embedded rel
                'products'  // xml element name
            ),
            'app_product_list', // route
            array('order' => 'desc'), // route parameters
            $pager->getCurrentPage(),       // page number
            $pager->getMaxPerPage(),      // limit
            $pager->getNbPages(),       // total pages
            'page',  // page route parameter name, optional, defaults to 'page'
            'limit', // limit route parameter name, optional, defaults to 'limit'
            true,   // generate relative URIs, optional, defaults to `false`
            $pager->getNbResults()   // total collection size, optional, defaults to `null`
        );

        return $paginatedCollection;

        //return new Products($pager);
        //return $pager->getCurrentPageResults();

        //TODO: pagination & representation
    }
}
