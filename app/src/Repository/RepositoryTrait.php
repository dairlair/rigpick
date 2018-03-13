<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

trait RepositoryTrait
{
    protected function itemsPerPage(): int
    {
        return 50;
    }

    public function findLatest(int $page = 1): Pagerfanta
    {
        /** @var ServiceEntityRepository $this */
        $query = $this->createQueryBuilder('t1')->getQuery();

        return $this->createPaginator($query, $page);
    }

    private function createPaginator(Query $query, int $page): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginator->setMaxPerPage($this->itemsPerPage());
        $paginator->setCurrentPage($page);
        return $paginator;
    }
}
