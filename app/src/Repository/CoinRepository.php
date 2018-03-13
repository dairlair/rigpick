<?php

namespace App\Repository;

use App\Entity\Algorithm;
use App\Entity\Coin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CoinRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Coin::class);
    }

    /**
     * @param Algorithm $algorithm
     * @param string $ticker
     *
     *
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @return Coin
     */
    public function findByTickerOrCreate(Algorithm $algorithm, string $ticker): Coin
    {
        $coin = $this->findOneBy(['algorithm' => $algorithm, 'ticker' => $ticker]);

        if (!$coin) {
            $coin = new Coin();
            $coin->setAlgorithm($algorithm);
            $coin->setTicker($ticker);
            $this->getEntityManager()->persist($coin);
            $this->getEntityManager()->flush();
        }

        return $coin;
    }

    public function findByTicker(string $ticker): ?Coin
    {
        $query = $this->createQueryBuilder('c')
                      ->andWhere('LOWER(c.ticker) = :ticker')
                      ->setParameter('ticker', strtolower($ticker))
                      ->getQuery();

        return $query->getSingleResult();
    }
}
