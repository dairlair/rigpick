<?php

namespace App\Repository;

use App\Entity\Rig;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RigRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Rig::class);
    }

    public function findByUserWithGpus(User $user)
    {
        $query = $this->createQueryBuilder('r')
            ->leftJoin('r.gpus', 'c')
            ->addSelect('c')
            ->andWhere('r.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
        ;

        return $query->getResult();
    }
}
