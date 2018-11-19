<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function search(int $limit, string $order, int $page)
    {

        $qb = $this
            ->createQueryBuilder('a')
            ->select('a')
            ->orderBy('a.id', $order);

        return $this->paginate($qb, $limit, $page);
    }

    protected function paginate(QueryBuilder $qb, int $limit, int $page)
    {

        $pager = new Pagerfanta(new DoctrineORMAdapter($qb));
        $pager->setAllowOutOfRangePages(true);
        $pager->setCurrentPage($page);
        $pager->setMaxPerPage($limit);

        return $pager;
    }


//    public function getList()
//    {
//        $queryBuilder = $this
//            ->createQueryBuilder('a')
//            ->select('a');
//
//        $adapter = new DoctrineORMAdapter($queryBuilder);
//        $pagerfanta = new Pagerfanta($adapter);
//
//        $pagerfanta->setMaxPerPage(5);
//        $pagerfanta->setCurrentPage(2);
//
//        dump($pagerfanta->getNbPages());
//        dump($pagerfanta->getCurrentPage());
//        dd($pagerfanta->getCurrentPageResults());
//    }


}
