<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="keys")
 * @ORM\Entity
 */
class Key
{
    public const PROVIDER_FACEBOOK = 'facebook';
    public const PROVIDER_GOOGLE = 'google';

    /** @ORM\ManyToOne(targetEntity="User") */
    private $user;

    /** @ORM\Id @ORM\Column(type="string") */
    private $provider;

    /** @ORM\Id @ORM\Column(type="string") */
    private $id;

    public function __construct($provider, $id)
    {
        $this->provider = $provider;
        $this->id = $id;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user) : void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getProvider() : string
    {
        return $this->provider;
    }

    /**
     * @param mixed $provider
     */
    public function setProvider($provider) : void
    {
        $this->provider = $provider;
    }

    /**
     * @return mixed
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) : void
    {
        $this->id = $id;
    }
}
