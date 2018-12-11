<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 22/11/2018
 * Time: 07:07
 */

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductService
{
    private $productRepository;
    private $manager;

    public function __construct(
        ProductRepository $productRepository,
        ObjectManager $manager
    ) {
        $this->productRepository       = $productRepository;
        $this->manager                 = $manager;
    }

    /**
     * RETURN PRODUCT DETAIL
     * @param string $productId
     * @return mixed
     */
    public function showProductDetail(string $productId)
    {
        // GET PRODUCT
        $result = $this->productRepository->getProductdetail($productId);

        if (null === $result) {
            throw new NotFoundHttpException('Product not Found');
        }

        return $result;
    }


    /**
     * RETURN A PAGINATED REPRESENTATION OF PRODUCTS
     * @param array $params
     * @return PaginatedRepresentation
     */
    public function showProductList(array $params):PaginatedRepresentation
    {
        // GET PRODUCTS
        $products = $this->getProductList($params);

        // MAKE REPRESENTATION
        $representationList = $this->getProductListRepresentation($products);

        return $representationList;
    }

    /**
     * GET PRODUCT LIST
     * @param array $params
     * @return array
     */
    public function getProductList(array $params)
    {
        $pager = $this
                    ->manager
                    ->getRepository(Product::class)
                    ->getList(
                        $params['limit'],
                        $params['order'],
                        $params['page']
                    );

        $dataRepresentation = [
            'currentResults' => $pager->getCurrentPageResults(),
            'currentPage'    => $pager->getCurrentPage(),
            'maxPerPage'     => $pager->getMaxPerPage(),
            'nbPages'        => $pager->getNbPages(),
            'nbResults'      => $pager->getNbResults(),
        ];

        return $dataRepresentation;
    }

    /**
     * MAKE THE PRODUCT LIST REPRESENTATION
     * @param array $data
     * @return PaginatedRepresentation
     */
    public function getProductListRepresentation(array $data):PaginatedRepresentation
    {
        $paginatedCollection = new PaginatedRepresentation(
            new CollectionRepresentation(
                $data['currentResults'],
                'products',
                'products'
            ),
            'app_product_list',
            array('order' => 'desc'),
            $data['currentPage'],
            $data['maxPerPage'],
            $data['nbPages'],
            'page',
            'limit',
            true,
            $data['nbResults']
        );

        return $paginatedCollection;
    }
}
