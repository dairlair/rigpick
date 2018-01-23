<?php

namespace App\Repository;

use App\Entity\GraphicCardSeries;
use App\Entity\Vendor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GraphicCardSeriesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GraphicCardSeries::class);
    }

    /**
     * @param Vendor $vendor
     * @param string $name
     *
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @return GraphicCardSeries
     */
    public function findByNameOrCreate(Vendor $vendor, string $name): GraphicCardSeries
    {
        $graphicCardSeries = $this->findOneBy(['vendor' => $vendor, 'name' => $name]);

        if (!$graphicCardSeries) {
            $graphicCardSeries = new GraphicCardSeries();
            $graphicCardSeries->setVendor($vendor);
            $graphicCardSeries->setName($name);
            $this->getEntityManager()->persist($graphicCardSeries);
            $this->getEntityManager()->flush();
        }

        return $graphicCardSeries;
    }
}
