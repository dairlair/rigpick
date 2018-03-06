<?php

namespace App\Repository;

use App\Entity\VgaBiosIndex;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\RegistryInterface;

class VgaBiosIndexRepository extends ServiceEntityRepository
{
    private const MAX_PER_PAGE = 50;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, VgaBiosIndex::class);
    }

    public function findLatest(int $page = 1): Pagerfanta
    {
        $query = $this->createQueryBuilder('t1')->getQuery();

        return $this->createPaginator($query, $page);
    }

    private function createPaginator(Query $query, int $page): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginator->setMaxPerPage(self::MAX_PER_PAGE);
        $paginator->setCurrentPage($page);
        return $paginator;
    }
}
