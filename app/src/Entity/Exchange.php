<?php declare(strict_types=1);

namespace App\Entity;

use DateTime;
/**
 * @ORM\Entity(repositoryClass="App\Repository\ExchangeRepository")
 * @ORM\Table(name="exchange", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="exchange_unique_name", columns={"name"})
 * })
 */
class Exchange
{
    const INTERNAL = 'internal';
    const WHAT_TO_MINE = 'what_to_mine';

    const SOURCES = [
        self::INTERNAL,
        self::WHAT_TO_MINE
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     */
    private $syncedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $reviewed = false;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $website;


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
     * @return DateTime
     */
    public function getSyncedAt(): DateTime
    {
        return $this->syncedAt;
    }

    /**
     * @param DateTime $syncedAt
     */
    public function setSyncedAt(DateTime $syncedAt): void
    {
        $this->syncedAt = $syncedAt;
    }


    /**
     * @return mixed
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
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

    /**
     * @return string
     */
    public function getWebsite(): string
    {
        return $this->website;
    }

    /**
     * @param string $website
     */
    public function setWebsite(string $website): void
    {
        $this->website = $website;
    }

}
