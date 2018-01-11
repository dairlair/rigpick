<?php

namespace App\Repository;

use App\Entity\GraphicCardModel;
use App\Entity\VgaBios;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class VgaBiosRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, VgaBios::class);
    }

    /**
     * @param GraphicCardModel $model
     * @param array            $attributes
     *
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @return VgaBios
     */
    public function findByAttributesOrCreate(GraphicCardModel $model, array $attributes): VgaBios
    {
        $vgaBios = $this->findOneBy(['model' => $model]);
        if (!$vgaBios) {
            [$memorySize] = explode(' ', $attributes['Memory Size']);
            [$gpuClock] = explode(' ', $attributes['GPU Clock']);
            [$memoryClock] = explode(' ', $attributes['Memory Clock']);
            $vgaBios = new VgaBios();
            $vgaBios->setModel($model);
            $vgaBios->setDeviceId(hexdec($attributes['Device Id']));
            $vgaBios->setSubsystemId(hexdec($attributes['Subsystem Id']));
            $vgaBios->setInterface($attributes['Interface']);
            $vgaBios->setMemorySize((int)$memorySize * 1024 * 1024);
            $vgaBios->setGpuClock((int)$gpuClock * 1024 * 1024);
            $vgaBios->setMemoryClock((int)$memoryClock * 1024 * 1024);
            $vgaBios->setMemoryType($attributes['Memory Type']);
            $this->getEntityManager()->persist($vgaBios);
            $this->getEntityManager()->flush();
        }
        return $vgaBios;
    }
}
