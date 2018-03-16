<?php

namespace App\DataFixtures;

use App\Entity\GraphicCardSeries;
use App\Entity\Vendor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class GraphicCardSeriesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getVendors() as $row) {
            ['pciSigId' => $pciSigId, 'name' => $name] = $row;

            /** @var Vendor $vendor */
            $vendor = $manager->getRepository(Vendor::class)->findOneBy(['pciSigId' => $pciSigId]);

            /** @var Vendor $vendor */
            $graphicCardSeries = $manager->getRepository(GraphicCardSeries::class)->findOneBy([
                'vendor' => $vendor,
                'name' => $name,
            ]);
            if ($graphicCardSeries) {
                continue;
            }

            $graphicCardSeries = new GraphicCardSeries();
            $graphicCardSeries->setVendor($vendor);
            $graphicCardSeries->setName($name);
            $manager->persist($graphicCardSeries);
        }

        $manager->flush();
    }

    protected function getVendors(): array
    {
        return [
            ['pciSigId' => 4318, 'name' => 'GTX 1060'],
            ['pciSigId' => 4318, 'name' => 'GTX 1070'],
        ];
    }

    public function getDependencies()
    {
        return array(
            VendorFixtures::class,
        );
    }
}
