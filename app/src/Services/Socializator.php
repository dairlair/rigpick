<?php

namespace App\Services;

use App\Entity\Key;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;

/**
 * This class can find user or create new by information received from OAuth2 providers.
 * @package App
 */
class Socializator
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function findOrCreate(string $provider, ResourceOwnerInterface $resourceOwner)
    {
        /** @var Key $key */
        $key = $this->em->getRepository(Key::class)->findOneBy([
            'provider' => $provider,
            'id' => $resourceOwner->getId(),
        ]);

        if ($key) {
            return $key->getUser();
        }

        // @TODO Wrap to transaction
        $user = $this->createUser($provider, $resourceOwner);

        $this->createKey($provider, $resourceOwner, $user);

        return $user;
    }

    protected function createUser(string $provider, ResourceOwnerInterface $resourceOwner) : User
    {
        $user = new User();
        $user->setUsername(md5(rand()));
        $user->setEmail(md5(rand()) . '@gmail.com');
        $user->setPassword('');
        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

    protected function createKey(string $provider, ResourceOwnerInterface $resourceOwner, User $user)
    {
        $key = new Key($provider, $resourceOwner->getId());
        $key->setUser($user);
        $this->em->persist($key);
        $this->em->flush();
    }
}