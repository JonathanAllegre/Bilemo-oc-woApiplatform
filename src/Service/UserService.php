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
use Pagerfanta\Pagerfanta;

class UserService
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }


    public function showUserList(Customer $customer)
    {
        // GET USER LIST
        $pager = $this
            ->manager
            ->getRepository(User::class)
            ->getList(
                $customer
            );

        return $pager;

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
                'users',
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
