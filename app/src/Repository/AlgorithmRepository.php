<?php

namespace App\Repository;

use App\Entity\Algorithm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class AlgorithmRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Algorithm::class);
    }

    /**
     * @param string $name
     *
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @return Algorithm
     */
    public function findByNameOrCreate(string $name): Algorithm
    {
        $algorithm = $this->findOneBy(['name' => $name]);

        if (!$algorithm) {
            $algorithm = new Algorithm();
            $this->getEntityManager()->persist($algorithm);
            $this->getEntityManager()->flush();
        }

        return $algorithm;
    }
}
