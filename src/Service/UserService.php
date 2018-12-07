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
use Hateoas\Representation\RouteAwareRepresentation;

class UserService
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * ADD USER
     * @param User $user
     * @param Customer $customer
     */
    public function addUser(User $user, Customer $customer)
    {
        $user->setCustomer($this->getUser());
        $this->manager->persist($user);
        $this->manager->flush();
    }

    /**
     * CHECK IF USER HAD CURRENT CUSTOMER
     * @param User $user
     * @param Customer $customer
     * @return bool
     */
    public function isUserHadCurrentCustomer(User $user, Customer $customer):bool
    {
        if ($user->getCustomer()->getId() === $customer->getId()) {
            return true;
        }

        return false;
    }

    /**
     * @param int $limit
     * @param string $order
     * @param int $page
     * @param Customer $customer
     * @return PaginatedRepresentation
     */
    public function showUserList(int $limit, string $order, int $page, Customer $customer):PaginatedRepresentation
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
