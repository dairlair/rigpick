<?php declare(strict_types = 1);

namespace App\Entity;

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
    private $model_vendor_name;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $series_vendor_name;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $model_name;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $series_name;

    /**
     * @return string
     */
    public function getModelVendorName(): string
    {
        return $this->model_vendor_name;
    }

    /**
     * @return string
     */
    public function getSeriesVendorName(): string
    {
        return $this->series_vendor_name;
    }

    /**
     * @return string
     */
    public function getModelName(): string
    {
        return $this->model_name;
    }

    /**
     * @return string
     */
    public function getSeriesName(): string
    {
        return $this->series_name;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function getFullName(): string
    {
        $parts = [
            $this->getModelVendorName(),
            $this->getModelName(),
            $this->getSeriesName(),
        ];
        $parts = array_filter($parts, function ($value) {
            return $value;
        });
        return implode(' ', $parts);
    }
}
