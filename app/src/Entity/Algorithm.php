<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AlgorithmRepository")
 * @ORM\Table(name="algorithms", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="algorithm_unique_ticker", columns={"ticker"})
 * })
 */
class Algorithm
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Coin")
     */
    private $coins;

    /**
     * @ORM\Column(type="text", unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="text", unique=true)
     */
    private $ticker;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;
    /**
     * @ORM\Column(type="boolean")
     */
    private $reviewed = false;

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
     * @return Coin
     */
    public function getCoins(): Coin
    {
        return $this->coins;
    }

    /**
     * @param Coin $coins
     */
    public function setCoins(Coin $coins): void
    {
        $this->coins = $coins;
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $ticker
     */
    public function setTicker(string $ticker): void
    {
        $this->ticker = $ticker;
    }

    /**
     * @return mixed
     */
    public function getTicker(): string
    {
        return $this->ticker;
    }

    /**
     * @param mixed $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return bool
     */
    public function getReviewed(): bool
    {
        return $this->reviewed;
    }

    /**
     * @param bool $reviewed
     */
    public function setReviewed(bool $reviewed): void
    {
        $this->reviewed = $reviewed;
    }
}
