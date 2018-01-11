<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GraphicCardModelRepository")
 * @ORM\Table(name="graphic_card_models", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="graphic_card_models_unique_vendor_name", columns={"vendor_id", "series_id", "name"})
 * })
 */
class GraphicCardModel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="GraphicCardSeries")
     */
    private $series;

    /**
     * @ORM\ManyToOne(targetEntity="Vendor")
     */
    private $vendor;

    /**
     * @ORM\Column(type="text")
     */
    private $name;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return GraphicCardSeries
     */
    public function getSeries(): GraphicCardSeries
    {
        return $this->series;
    }

    /**
     * @param GraphicCardSeries $series
     */
    public function setSeries(GraphicCardSeries $series): void
    {
        $this->series = $series;
    }

    /**
     * @return Vendor
     */
    public function getVendor(): Vendor
    {
        return $this->vendor;
    }

    /**
     * @param Vendor $vendor
     */
    public function setVendor(Vendor $vendor): void
    {
        $this->vendor = $vendor;
    }

    /**
     * @return mixed
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }
}
