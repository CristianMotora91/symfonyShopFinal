<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }


    public function load(ObjectManager $manager): void
    {
        $user = new User();

        $user->setEmail('admin@admin.ro')
            ->setPassword($this->passwordEncoder->encodePassword($user, 'admin'))
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);

        $user1 = new User();

        $user1->setEmail('user@user.ro')
            ->setPassword($this->passwordEncoder->encodePassword($user, 'user'))
            ->setRoles(['ROLE_USER']);

        $manager->persist($user1);

        $manager->flush();
    }
}
