<?php declare(strict_types = 1);

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RigRepository")
 * @ORM\Table(name="rigs", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="rigs_unique_hash", columns={"hash"}),
 *     @ORM\UniqueConstraint(name="rigs_unique_name", columns={"name"})
 * })
 */
class Rig
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="bigint")
     * @var integer
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @Assert\NotBlank()
     * @var User
     */
    private $user;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 6,
     *      max = 6,
     *      minMessage = "Hash must be at least {{ limit }} characters long",
     *      maxMessage = "Hash cannot be longer than {{ limit }} characters"
     * )
     * @var string
     */
    private $hash;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\Length(min = 4,max = 40)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(max = 16384)
     * @var string
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var integer
     */
    private $power;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     * @var DateTime
     */
    private $createdAt;

    /**
     * One Rig have many RigGpu.
     * @ORM\OneToMany(targetEntity="RigGpu", mappedBy="rig")
     * @var array
     */
    private $gpus;

    public function __construct()
    {
        $this->gpus = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id ? (int) $this->id : null;
    }

    /**
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string|null
     */
    public function getHash(): ?string
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     */
    public function setHash(string $hash): void
    {
        $this->hash = $hash;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
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
     * @return string|null
     */
    public function getDescription(): ?string
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
     * @return int|null
     */
    public function getPower(): ?int
    {
        return $this->power;
    }

    /**
     * @param int $power
     */
    public function setPower(int $power): void
    {
        $this->power = $power;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getGpus()
    {
        return $this->gpus;
    }
}
