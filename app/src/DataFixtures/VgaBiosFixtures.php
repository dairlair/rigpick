<?php

namespace App\DataFixtures;

use App\Entity\GraphicCardModel;
use App\Entity\GraphicCardSeries;
use App\Entity\Vendor;
use App\Entity\VgaBios;
use App\Entity\VgaBiosIndex;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use RuntimeException;

class VgaBiosFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     *
     * @throws RuntimeException
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->getGraphicCards() as $graphicCard) {
            /** @var Vendor $modelVendor */
            $modelVendor = $manager->getRepository(Vendor::class)->findOneBy(['pciSigId' => $graphicCard['modelPciSigId']]);
            /** @var Vendor $seriesVendor */
            $seriesVendor = $manager->getRepository(Vendor::class)->findOneBy(['pciSigId' => $graphicCard['seriesPciSigId']]);

            /** @var GraphicCardSeries $graphicCardSeries */
            $graphicCardSeries = $manager->getRepository(GraphicCardSeries::class)->findOneBy([
                'vendor' => $seriesVendor,
                'name' => $graphicCard['seriesName'],
            ]);

            /** @var GraphicCardModel $graphicCardModel */
            $graphicCardModel = $manager->getRepository(GraphicCardModel::class)->findOneBy([
                'vendor' => $modelVendor,
                'series' => $graphicCardSeries,
                'name' => $graphicCard['modelName'],
            ]);

            if (!$graphicCardModel) {
                throw new RuntimeException('Graphic card must have model');
            }

            $vgaBios = $manager->getRepository(VgaBios::class)->findOneBy([
                'model' => $graphicCardModel,
                'deviceId' => $graphicCard['deviceId'],
                'subsystemId' => $graphicCard['subsystemId'],
            ]);

            if ($vgaBios) {
                continue;
            }

            $vgaBios = new VgaBios();
            $vgaBios->setModel($graphicCardModel);
            $vgaBios->setDeviceId($graphicCard['deviceId']);
            $vgaBios->setSubsystemId($graphicCard['subsystemId']);
            $vgaBios->setInterface($graphicCard['interface']);
            $vgaBios->setMemorySize($graphicCard['memorySize']);
            $vgaBios->setGpuClock($graphicCard['gpuClock']);
            $vgaBios->setMemoryClock($graphicCard['memoryClock']);
            $vgaBios->setMemoryType($graphicCard['memoryType']);
            $manager->persist($vgaBios);
        }

        $manager->flush();
    }

    public function getGraphicCards(): array
    {
        return [
            [
                'seriesName' => 'GTX 1060',
                'seriesPciSigId' => 4318, // NVidia
                'modelPciSigId' => 5218, // MSI
                'modelName' => 'Sea Hawk',
                'deviceId' => 282991489,
                'subsystemId' => 341979907,
                'interface' => 'PCI-E',
                'memorySize' => 8589934592,
                'gpuClock' => 1712324608,
                'memoryClock' => 2125463552,
                'memoryType' => 'GDDR5',
            ]
        ];
    }


    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            GraphicCardModelFixtures::class
        );
    }
}
