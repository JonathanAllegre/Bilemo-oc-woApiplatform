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


    public function showUserList(array $params, Customer $customer)
    {
        // GET USER LIST
        $users = $this->getUserList($params, $customer);

        // MAKE REPRESENTATION
        $representationList = $this->getUserListRepresentation($users);

        return $representationList;
    }

    /**
     * GET USER LIST
     * @param array $params
     * @param Customer $customer
     * @return array
     */
    public function getUserList(array $params, Customer $customer)
    {
        $pager = $this
            ->manager
            ->getRepository(User::class)
            ->getList(
                $params['limit'],
                $params['order'],
                $params['page'],
                $customer
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
