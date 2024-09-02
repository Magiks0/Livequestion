<?php

namespace App\DataFixtures;

use App\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class QuestionFixtures extends Fixture implements DependentFixtureInterface
{
    public const REFERENCE_IDENTIFIER = "question_";

    public const QUESTIONS = [
        [
            'title' => 'Aimez-vous le dernier Marvel (Deadpool & Wolverine) ?',
            'category' => '3',
            'author' => '0',
            'createdAt' => '2024-08-20 10:00:00' // Aléatoire entre aujourd'hui et une semaine avant
        ],
        [
            'title' => 'Est-ce-que vous avez regardé la finale par équipe de judo ?',
            'category' => '2',
            'author' => '1',
            'createdAt' => '2024-08-20 11:00:00'
        ],
        [
            'title' => 'Quels sont vos rangs valorant ?',
            'category' => '5',
            'author' => '0',
            'createdAt' => '2024-08-15 12:00:00'
        ],
        [
            'title' => 'Quel est votre album de musique préféré cette année ?',
            'category' => '0', // Musique
            'author' => '2',
            'createdAt' => '2024-08-14 13:00:00'
        ],
        [
            'title' => 'Quelle est votre équipe préférée dans le championnat de football ?',
            'category' => '2', // Sports
            'author' => '4',
            'createdAt' => '2024-08-21 15:00:00'
        ],
        [
            'title' => 'Quel film de science-fiction attendez-vous le plus cette année ?',
            'category' => '3', // Films
            'author' => '5',
            'createdAt' => '2024-08-21 16:00:00'
        ],
        [
            'title' => 'Quel est votre avis sur les récentes réformes politiques ?',
            'category' => '4', // Politique
            'author' => '6',
            'createdAt' => '2024-08-17 17:00:00'
        ],
        [
            'title' => 'Quel est le jeu vidéo que vous avez le plus apprécié cette année ?',
            'category' => '5', // Jeux vidéo
            'author' => '4',
            'createdAt' => '2024-08-19 18:00:00'
        ],
        [
            'title' => 'Quel est votre sport favori et pourquoi ?',
            'category' => '2', // Sports
            'author' => '3',
            'createdAt' => '2024-08-15 21:00:00'
        ],
        [
            'title' => 'Quels sont les films que vous recommandez pour un marathon de cinéma ?',
            'category' => '3', // Films
            'author' => '4',
            'createdAt' => '2024-08-14 22:00:00'
        ],
        [
            'title' => 'Quelles sont les politiques publiques que vous aimeriez voir mises en place ?',
            'category' => '4', // Politique
            'author' => '5',
            'createdAt' => '2024-08-16 23:00:00'
        ],
        [
            'title' => 'Quel est votre genre musical préféré et pourquoi ?',
            'category' => '0', // Musique
            'author' => '1',
            'createdAt' => '2024-08-17 10:00:00'
        ],
    ];

/**
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        foreach (self::QUESTIONS as $i => $question) {
            $quest = (new Question())
                ->setTitle($question['title'])
                ->setCategory($this->getReference(CategoryFixtures::REFERENCE_IDENTIFIER.$question['category']))
                ->setAuthor($this->getReference(UserFixtures::REFERENCE_IDENTIFIER.$question['author']))
                ->setCreatedAt(new \DateTimeImmutable($question['createdAt']))
                ->setFile($this->getReference(FileFixtures::REFERENCE_IDENTIFIER.$i));

            $manager->persist($quest);
            $this->addReference(self::REFERENCE_IDENTIFIER.$i, $quest);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CategoryFixtures::class,
            FileFixtures::class,
        ];
    }
}
