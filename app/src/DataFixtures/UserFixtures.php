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
        // Create 20 users!
        for ($i = 0; $i < 20; $i++) {
            $email = 'user' . $i . '@gmail.com';
            $user = $manager->getRepository(User::class)->findBy(['email' => $email]);
            if ($user) {
                continue;
            }
            $user = new User();
            $user->setEmail($email);
            $user->setUsername(md5(random_int(0, PHP_INT_MAX)));
            $password = $this->encoder->encodePassword($user, 'pass_1234');
            $user->setPassword($password);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
