<?php

namespace App\DataFixtures;

use App\Entity\Vendor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class VendorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getVendors() as $row) {
            ['pciSigId' => $pciSigId, 'name' => $name, 'brand' => $brand] = $row;
            /** @var Vendor $vendor */
            $vendor = $manager->getRepository(Vendor::class)->findOneBy(['pciSigId' => $pciSigId]);
            if ($vendor) {
                continue;
            }
            $vendor = new Vendor();
            $vendor->setPciSigId($pciSigId);
            $vendor->setName($name);
            $vendor->setBrand($brand);
            $manager->persist($vendor);
        }

        $manager->flush();
    }

    protected function getVendors(): array
    {
        return [
            ['pciSigId' => 4098, 'name' => 'AMD', 'brand' => 'AMD'],
            ['pciSigId' => 4318, 'name' => 'NVidia Corporation', 'brand' => 'NVIDIA'],
            ['pciSigId' => 5218, 'name' => 'Micro-Star International Co ., Ltd.', 'brand' => 'MSI'],
        ];
    }

}
