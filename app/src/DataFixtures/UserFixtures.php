<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // Create 20 users! Bam!
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setEmail('user' . $i . '@gmail.com');
            $user->setUsername(md5(mt_rand(0, PHP_INT_MAX)));
            $password = $this->encoder->encodePassword($user, 'pass_1234');
            $user->setPassword($password);
            $manager->persist($user);
        }

        $manager->flush();
    }
}