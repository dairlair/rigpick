<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VgaBiosRepository")
 * @ORM\Table(name="vga_bios")
 */
class VgaBios
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="GraphicCardModel")
     */
    private $model;

    /**
     * @ORM\Column(type="bigint")
     */
    private $deviceId;

    /**
     * @ORM\Column(type="bigint")
     */
    private $subsystemId;

    /**
     * @ORM\Column(type="string")
     */
    private $interface;

    /**
     * @ORM\Column(type="bigint")
     */
    private $memorySize;

    /**
     * @ORM\Column(type="bigint")
     */
    private $gpuClock;

    /**
     * @ORM\Column(type="bigint")
     */
    private $memoryClock;

    /**
     * @ORM\Column(type="string")
     */
    private $memoryType;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param mixed $model
     */
    public function setModel($model): void
    {
        $this->model = $model;
    }

    /**
     * @return int
     */
    public function getDeviceId(): int
    {
        return $this->deviceId;
    }

    /**
     * @param mixed $deviceId
     */
    public function setDeviceId(int $deviceId): void
    {
        $this->deviceId = $deviceId;
    }

    /**
     * @return int
     */
    public function getSubsystemId(): int
    {
        return $this->subsystemId;
    }

    /**
     * @param int $subsystemId
     */
    public function setSubsystemId(int $subsystemId): void
    {
        $this->subsystemId = $subsystemId;
    }

    /**
     * @return string
     */
    public function getInterface(): string
    {
        return $this->interface;
    }

    /**
     * @param string $interface
     */
    public function setInterface(string $interface): void
    {
        $this->interface = $interface;
    }

    /**
     * @return int
     */
    public function getMemorySize(): int
    {
        return $this->memorySize;
    }

    /**
     * @param int $memorySize
     */
    public function setMemorySize(int $memorySize): void
    {
        $this->memorySize = $memorySize;
    }

    /**
     * @return int
     */
    public function getGpuClock(): int
    {
        return $this->gpuClock;
    }

    /**
     * @param int $gpuClock
     */
    public function setGpuClock(int $gpuClock): void
    {
        $this->gpuClock = $gpuClock;
    }

    /**
     * @return int
     */
    public function getMemoryClock(): int
    {
        return $this->memoryClock;
    }

    /**
     * @param int $memoryClock
     */
    public function setMemoryClock(int $memoryClock): void
    {
        $this->memoryClock = $memoryClock;
    }

    /**
     * @return string
     */
    public function getMemoryType(): string
    {
        return $this->memoryType;
    }

    /**
     * @param string $memoryType
     */
    public function setMemoryType(string $memoryType): void
    {
        $this->memoryType = $memoryType;
    }
}
