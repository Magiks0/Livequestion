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
            'firstname' => 'Jeff',
            'lastname' => 'Martins',
            'email' => 'example@example.com',
            'role' => 'ROLE_AUTHOR',
        ],
        [
            'firstname' => 'Jean',
            'lastname' => 'Claude',
            'email' => 'example2@example.com',
            'role' => 'ROLE_AUTHOR',
        ],
        [
            'firstname' => 'Marie',
            'lastname' => 'Dubois',
            'email' => 'marie.dubois@example.com',
            'role' => 'ROLE_AUTHOR',
        ],
        [
            'firstname' => 'Paul',
            'lastname' => 'Martin',
            'email' => 'paul.martin@example.com',
            'role' => 'ROLE_AUTHOR',
        ],
        [
            'firstname' => 'Sophie',
            'lastname' => 'Leroux',
            'email' => 'sophie.leroux@example.com',
            'role' => 'ROLE_AUTHOR',
        ],
        [
            'firstname' => 'Lucas',
            'lastname' => 'Moreau',
            'email' => 'lucas.moreau@example.com',
            'role' => 'ROLE_AUTHOR',
        ],
        [
            'firstname' => 'Emma',
            'lastname' => 'Carpentier',
            'email' => 'emma.carpentier@example.com',
            'role' => 'ROLE_AUTHOR',
        ],
        [
            'firstname' => 'Hugo',
            'lastname' => 'Roux',
            'email' => 'hugo.roux@example.com',
            'role' => 'ROLE_AUTHOR',
        ],
        [
            'firstname' => 'Clara',
            'lastname' => 'Petit',
            'email' => 'clara.petit@example.com',
            'role' => 'ROLE_AUTHOR',
        ],
        [
            'firstname' => 'Léa',
            'lastname' => 'Garcia',
            'email' => 'lea.garcia@example.com',
            'role' => 'ROLE_AUTHOR',
        ],
        [
            'firstname' => 'Maxime',
            'lastname' => 'Dupont',
            'email' => 'maxime.dupont@example.com',
            'role' => 'ROLE_AUTHOR',
        ],
        [
            'firstname' => 'Chloé',
            'lastname' => 'Bernard',
            'email' => 'chloe.bernard@example.com',
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
            $user->setFirstname($appUser['firstname'])
                ->setLastname($appUser['lastname'])
                ->setEmail($appUser['email'])
                ->setRoles([$appUser['role']])
                ->setPassword($this->passwordHasher->hashPassword($user, 'xxx'));

            $manager->persist($user);
            $this->addReference(self::REFERENCE_IDENTIFIER.$i, $user);
        }

        $manager->flush();
    }
}
