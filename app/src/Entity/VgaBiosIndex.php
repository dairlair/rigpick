<?php declare(strict_types = 1);

namespace App\Entity;

use App\Helpers\UnitsHelper;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VgaBiosIndexRepository", readOnly=true)
 * @ORM\Table(name="vga_bios_index")
 */
class VgaBiosIndex
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $modelVendorName;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $seriesVendorName;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $modelName;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $seriesName;

    /**
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $memorySize;

    /**
     * @return string
     */
    public function getModelVendorName(): string
    {
        return $this->modelVendorName;
    }

    /**
     * @return string
     */
    public function getSeriesVendorName(): string
    {
        return $this->seriesVendorName;
    }

    /**
     * @return string
     */
    public function getModelName(): string
    {
        return $this->modelName;
    }

    /**
     * @return string
     */
    public function getSeriesName(): string
    {
        return $this->seriesName;
    }

    /**
     * @return int
     */
    public function getVgaBiosId(): int
    {
        return $this->id;
    }

    public function getMemorySize(): ?int
    {
        return $this->memorySize;
    }


    public function getMemorySizeString(): ?string
    {
        if ($this->getMemorySize() === null) {
            return null;
        }

        return UnitsHelper::formatBytes($this->getMemorySize());
    }

    public function getFullName(): string
    {
        $parts = [
            $this->getModelVendorName(),
            $this->getModelName(),
            $this->getSeriesName(),
            $this->getMemorySizeString()
        ];
        $parts = array_filter($parts, function ($value) {
            return $value;
        });
        return implode(' ', $parts);
    }

    public function getSlug(): string
    {
        $slugify = new Slugify();
        return $slugify->slugify($this->getFullName());
    }
}
