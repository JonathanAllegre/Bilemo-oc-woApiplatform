<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Psr\SimpleCache\CacheInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Cache\Simple\ApcuCache;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    private $cache;

    public function __construct(RegistryInterface $registry, CacheInterface $cache)
    {
        $this->cache = $cache;
        parent::__construct($registry, Product::class);
    }

    /**
     * @param int $limit
     * @param string $order
     * @param int $page
     * @return Pagerfanta
     */
    public function search(int $limit, string $order, int $page)
    {
        $querybuilder = $this
            ->createQueryBuilder('a')
            ->select('a')
            ->orderBy('a.id', $order)
            ->getQuery()
            ->useResultCache(true, 3600, 'product_result');

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
