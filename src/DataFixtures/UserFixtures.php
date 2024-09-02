<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const REFERENCE_IDENTIFIER = 'user_';

    public const USERS = [
        [
            'username' => 'Jeff Martins',
            'email' => 'example@example.com',
            'role' => 'ROLE_AUTHOR',
        ],
        [
            'username' => 'Jean Claude',
            'email' => 'example2@example.com',
            'role' => 'ROLE_AUTHOR',
        ],
        [
            'username' => 'Marie Dubois',
            'email' => 'marie.dubois@example.com',
            'role' => 'ROLE_AUTHOR',
        ],
        [
            'username' => 'Paul Martin',
            'email' => 'paul.martin@example.com',
            'role' => 'ROLE_AUTHOR',
        ],
        [
            'username' => 'Sophie Leroux',
            'email' => 'sophie.leroux@example.com',
            'role' => 'ROLE_AUTHOR',
        ],
        [
            'username' => 'Lucas Moreau',
            'email' => 'lucas.moreau@example.com',
            'role' => 'ROLE_AUTHOR',
        ],
        [
            'username' => 'Emma Carpentier',
            'email' => 'emma.carpentier@example.com',
            'role' => 'ROLE_AUTHOR',
        ],
    ];

    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::USERS as $i => $appUser) {
            $user = new User();
            $user->setUsername($appUser['username'])
                ->setEmail($appUser['email'])
                ->setRoles([$appUser['role']])
                ->setPassword($this->passwordHasher->hashPassword($user, 'xxx'));

            $manager->persist($user);
            $this->addReference(self::REFERENCE_IDENTIFIER.$i, $user);
        }

        $manager->flush();
    }
}
