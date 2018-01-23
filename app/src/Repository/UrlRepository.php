<?php

namespace App\Repository;

use App\Entity\Url;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UrlRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Url::class);
    }

    public function findByUrl(string $url): ?Url
    {
        /** @var Url|null $entity */
        $entity = $this->findOneBy(['url' => $url]);

        return $entity;
    }

    /**
     * @param string $url
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @return Url
     */
    public function findByUrlOrCreate(string $url): Url
    {
        /** @var Url $entity */
        $entity = $this->findOneBy(['url' => $url]);

        if (!$entity) {
            $entity = new Url();
            $entity->setUrl($url);
        }

        $entity->setUpdatedAt(new \DateTime());
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

        return $entity;
    }
}
