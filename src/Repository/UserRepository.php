<?php

namespace App\Repository;

use App\Entity\Customer;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

//    public function getList(int $limit, string $order, int $page, Customer $customer)
//    {
//        $querybuilder = $this
//            ->createQueryBuilder('a')
//            ->select('a')
//            ->andWhere('a.customer = ?1')
//            ->orderBy('a.id', $order)
//            ->setParameter(1, $customer->getId())
//            ->getQuery();
//            //->useResultCache(true, 3600);
//
//        return $this->paginate($querybuilder, $limit, $page);
//    }

    public function getList(Customer $customer)
    {

        $users = $this->findByCustomer($customer);

        return $users;

        //return $this->paginate($users);
    }


    protected function paginate(array $querybuilder)
    {
        $pager = new Pagerfanta(new ArrayAdapter($querybuilder));
        $pager->setAllowOutOfRangePages(true);
        $pager->setCurrentPage(1);
        $pager->setMaxPerPage(5);

        return $pager;
    }
}
