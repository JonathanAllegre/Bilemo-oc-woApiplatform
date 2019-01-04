<?php

namespace App\Controller;

use App\Service\ProductService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Product;

class ProductController extends AbstractController
{

    /**
     * @SWG\Get(
     *     tags={"Product"},
     *     summary="Get the detail of the product",
     *     description="Return one product",
     *     @SWG\Response(
     *          response=200,
     *          description="Success",
     *          @Model(
     *              type=Product::class
     *          )
     *     ),
     *     @SWG\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="code",
     *                  type="integer",
     *                  example=401
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string",
     *                  example={"Expired JWT Token", "Invalid JWT Token"}
     *              )
     *          )
     *     )
     * )
     * @Rest\Get(
     *     path = "/api/products/{productId}",
     *     name = "app_product_show",
     *     requirements = {"productId"="\d+"}
     * )
     *
     * @Rest\View()
     */
    public function showAction(ProductService $productService, $productId)
    {
        return  $productService->showProductDetail($productId);
    }

    /**
     * @SWG\Get(
     *     tags={"Product"},
     *     summary="Get the list of products",
     *     description="Return a paginated collection of products",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Response(
     *          response=200,
     *          description="Success",
     *          @SWG\Schema(
     *                  @Model(type=Product::class)
     *          )
     *
     *     ),
     *     @SWG\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="code",
     *                  type="integer",
     *                  example=401
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string",
     *                  example={"Expired JWT Token", "Invalid JWT Token"}
     *              )
     *          )
     *     )
     * )
     * @Rest\Get(
     *     path = "/api/products",
     *     name = "app_product_list",
     * )
     *
     * @Rest\QueryParam(
     *     name="order",
     *     requirements="asc|desc",
     *     default="asc",
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
     */
    public function listAction(ParamFetcherInterface $paramFetcher, ProductService $productService)
    {
        $paginatedCollection = $productService->showProductList([
            'limit' => $paramFetcher->get('limit'),
            'order' => $paramFetcher->get('order'),
            'page'  => $paramFetcher->get('page'),
            ]);


        return $paginatedCollection;
    }
}
