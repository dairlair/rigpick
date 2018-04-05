<?php

namespace App\Repository;

use App\Entity\Exchange;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ExchangeRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Exchange::class);
    }

    /**
     * @param string $name
     * @return Exchange
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function findByNameOrCreate(string $name): Exchange
    {
        $exchange = $this->findOneBy(['name' => $name]);

        if (!$exchange) {
            $exchange = new Exchange();
            $exchange->setName($name);
            $this->getEntityManager()->persist($exchange);
            $this->getEntityManager()->flush();
        }

        return $exchange;
    }

    /**
     * @param string $name
     * @return Exchange|null
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByName(string $name): ?Exchange
    {
        $query = $this->createQueryBuilder('c')
                      ->andWhere('c.ticker = :ticker')
                      ->setParameter('ticker', strtolower($name))
                      ->getQuery();

        return $query->getSingleResult();
    }
}
