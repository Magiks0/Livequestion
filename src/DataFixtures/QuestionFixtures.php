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
            'author' => '0'
        ],
        [
            'title' => 'Est-ce-que vous avez regardé la finale par équipe de judo ?',
            'category' => '2',
            'author' => '1'
        ],
        [
            'title' => 'Quels sont vos rangs valorant ?',
            'category' => '5',
            'author' => '0'
        ]
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::QUESTIONS as $i => $question) {
            $quest = (new Question())
                ->setTitle($question['title'])
                ->setCategory($this->getReference(CategoryFixtures::REFERENCE_IDENTIFIER.$question['category']))
                ->setAuthor($this->getReference(UserFixtures::REFERENCE_IDENTIFIER.$question['author']))
                ->setCreatedAt(new \DateTimeImmutable())
                ->setFile('image_de_la_question');

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
        ];
    }
}
