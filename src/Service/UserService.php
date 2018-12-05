<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 29/11/2018
 * Time: 08:03
 */

namespace App\Service;

use App\Entity\Customer;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation;

class UserService
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }


    public function showUserList(int $limit, string $order, int $page, Customer $customer)
    {
        // GET USER LIST
        $pager = $this
            ->manager
            ->getRepository(User::class)
            ->getList(
                $limit,
                $order,
                $page,
                $customer
            );

        $dataRepresentation = [
            'currentResults' => $pager->getCurrentPageResults(),
            'currentPage'    => $pager->getCurrentPage(),
            'maxPerPage'     => $pager->getMaxPerPage(),
            'nbPages'        => $pager->getNbPages(),
            'nbResults'      => $pager->getNbResults(),
        ];

        return $this->getUserListRepresentation($dataRepresentation);
    }

    /**
     * MAKE THE PRODUCT LIST REPRESENTATION
     * @param array $data
     * @return PaginatedRepresentation
     */
    public function getUserListRepresentation(array $data):PaginatedRepresentation
    {
        $paginatedCollection = new PaginatedRepresentation(
            new CollectionRepresentation(
                $data['currentResults'],
                'users'
            ),
            'app_user_list',
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
