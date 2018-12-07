<?php

namespace App\Repository;

use App\Entity\Customer;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
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



    public function getList(int $limit, string $order, int $page, Customer $customer)
    {
        $querybuilder = $this
            ->createQueryBuilder('a')
            ->select('a')
            ->andWhere('a.customer = ?1')
            ->orderBy('a.id', $order)
            ->setParameter(1, $customer->getId())
            ->getQuery();
        //->useResultCache(true, 3600, 'list_user');

        return $this->paginate($querybuilder, $limit, $page);
    }


    protected function paginate(Query $querybuilder, int $limit, int $page)
    {
        $pager = new Pagerfanta(new DoctrineORMAdapter($querybuilder));
        $pager->setAllowOutOfRangePages(true);
        $pager->setCurrentPage($page);
        $pager->setMaxPerPage($limit);

        return $pager;
    }
}
