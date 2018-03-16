<?php

namespace App\DataFixtures;

use App\Entity\GraphicCardModel;
use App\Entity\GraphicCardSeries;
use App\Entity\Vendor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class GraphicCardModelFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getVendors() as $row) {
            ['pciSigId' => $pciSigId, 'name' => $name, 'seriesName' => $seriesName, 'seriesPciSigId' => $seriesPciSigId] = $row;

            /** @var Vendor $modelVendor */
            $modelVendor = $manager->getRepository(Vendor::class)->findOneBy(['pciSigId' => $pciSigId]);
            /** @var Vendor $seriesVendor */
            $seriesVendor = $manager->getRepository(Vendor::class)->findOneBy(['pciSigId' => $seriesPciSigId]);

            /** @var GraphicCardSeries $graphicCardSeries */
            $graphicCardSeries = $manager->getRepository(GraphicCardSeries::class)->findOneBy([
                'vendor' => $seriesVendor,
                'name' => $seriesName,
            ]);

            $graphicCardModel = $manager->getRepository(GraphicCardModel::class)->findOneBy([
                'vendor' => $modelVendor,
                'series' => $graphicCardSeries,
                'name' => $name,
            ]);

            if ($graphicCardModel) {
                continue;
            }

            $graphicCardModel = new GraphicCardModel();
            $graphicCardModel->setVendor($modelVendor);
            $graphicCardModel->setSeries($graphicCardSeries);
            $graphicCardModel->setName($name);
            $manager->persist($graphicCardModel);
        }

        $manager->flush();
    }

    protected function getVendors(): array
    {
        return [
            ['pciSigId' => 5218/* MSI */, 'name' => 'Sea Hawk',  'seriesName' => 'GTX 1060', 'seriesPciSigId' => 4318/* NVidia */],
            ['pciSigId' => 5218/* MSI */, 'name' => 'Armor OC',  'seriesName' => 'GTX 1060', 'seriesPciSigId' => 4318/* NVidia */],
        ];
    }

    public function getDependencies()
    {
        return array(
            VendorFixtures::class,
            GraphicCardSeriesFixtures::class,
        );
    }
}
