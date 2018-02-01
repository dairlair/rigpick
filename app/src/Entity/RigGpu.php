<?php

namespace App\Entity;

use App\Repository\VgaBiosIndexRepository;
use App\Repository\VgaBiosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RigGpuRepository")
 */
class RigGpu
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="bigint")
     * @var integer
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Rig", inversedBy="gpus")
     * @Assert\NotBlank()
     * @var Rig
     */
    private $rig;

    /**
     * @ORM\ManyToOne(targetEntity="VgaBios")
     * @Assert\NotBlank()
     * @var VgaBios
     */
    private $vgaBios;

    /**
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $count;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var integer
     */
    private $powerLimitPercentage;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var integer
     */
    private $gpuClock;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @var integer
     */
    private $gpuVoltage;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var integer
     */
    private $memoryClock;

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
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return Rig
     */
    public function getRig(): Rig
    {
        return $this->rig;
    }

    /**
     * @param Rig $rig
     */
    public function setRig(Rig $rig): void
    {
        $this->rig = $rig;
    }

    /**
     * @return VgaBios|null
     */
    public function getVgaBios(): ?VgaBios
    {
        return $this->vgaBios;
    }

    /**
     * @param VgaBios $vgaBios
     */
    public function setVgaBios(VgaBios $vgaBios): void
    {
        $this->vgaBios = $vgaBios;
    }

    /**
     * @return int|null
     */
    public function getCount(): ?int
    {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function setCount(int $count): void
    {
        $this->count = $count;
    }

    /**
     * @return int|null
     */
    public function getPowerLimitPercentage(): ?int
    {
        return $this->powerLimitPercentage;
    }

    /**
     * @param int $powerLimitPercentage
     */
    public function setPowerLimitPercentage(int $powerLimitPercentage): void
    {
        $this->powerLimitPercentage = $powerLimitPercentage;
    }

    /**
     * @return int|null
     */
    public function getGpuClock(): ?int
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
     * @return int|null
     */
    public function getGpuVoltage(): ?int
    {
        return $this->gpuVoltage;
    }

    /**
     * @param int $gpuVoltage
     */
    public function setGpuVoltage(int $gpuVoltage): void
    {
        $this->gpuVoltage = $gpuVoltage;
    }

    /**
     * @return int|null
     */
    public function getMemoryClock(): ?int
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
}
