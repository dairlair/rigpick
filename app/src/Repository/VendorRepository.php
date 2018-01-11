<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Vendor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bridge\Doctrine\RegistryInterface;

class VendorRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Vendor::class);
    }

    /**
     * @param int $pciSigId
     *
     * @throws EntityNotFoundException
     *
     * @return Vendor
     */
    public function findByPciSigIdOrFail(int $pciSigId): Vendor
    {
        /** @var Vendor $vendor */
        $vendor = $this->findOneBy(['pciSigId' => $pciSigId]);

        if (!$vendor) {
            throw new EntityNotFoundException('Vendor PCI-SIG #' . $pciSigId . ' not found.');
        }

        return $vendor;
    }

    /**
     * @param string $name
     *
     * @param int    $pciSigId
     *
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @return Vendor
     */
    public function findByNameOrCreate(string $name, int $pciSigId): Vendor
    {
        $vendor = $this->findOneBy(['pciSigId' => $pciSigId]);

        if (!$vendor) {
            $vendor = $this->findOneBy(['name' => $name]);
        }

        if (!$vendor) {
            $vendor = new Vendor();
            $vendor->setName($name);
            $vendor->setPciSigId($pciSigId);
            $this->getEntityManager()->persist($vendor);
            $this->getEntityManager()->flush();
        }

        return $vendor;
    }
}
