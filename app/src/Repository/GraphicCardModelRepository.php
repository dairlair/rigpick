<?php

namespace App\Repository;

use App\Entity\GraphicCardModel;
use App\Entity\GraphicCardSeries;
use App\Entity\Vendor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GraphicCardModelRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GraphicCardModel::class);
    }

    /**
     * @param GraphicCardSeries $series
     * @param Vendor            $vendor
     * @param string            $name
     *
     *
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @return GraphicCardModel
     */
    public function findByNameOrCreate(GraphicCardSeries $series, Vendor $vendor, string $name): GraphicCardModel
    {
        $graphicCardModel = $this->findOneBy(['series' => $series, 'name' => $name, 'vendor' => $vendor]);

        if (!$graphicCardModel) {
            $graphicCardModel = new GraphicCardModel();
            $graphicCardModel->setSeries($series);
            $graphicCardModel->setVendor($vendor);
            $graphicCardModel->setName($name);
            $this->getEntityManager()->persist($graphicCardModel);
            $this->getEntityManager()->flush();
        }

        return $graphicCardModel;
    }
}
