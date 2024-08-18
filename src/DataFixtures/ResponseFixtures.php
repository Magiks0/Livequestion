<?php

namespace App\DataFixtures;

use App\Entity\Response;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ResponseFixtures extends Fixture implements DependentFixtureInterface
{

    public const REFERENCE_IDENTIFIER = 'response_';

    public const RESPONSES = [
        [
            'content' => "Oui et je l'ai trouvé incroyable !",
            'question' => '0',
            'author' => '1',
        ],
        [
            'content' => "Oui on est les meilleurs merci Teddy :)",
            'question' => '1',
            'author' => '0',
        ],
        [
            'content' => "Je suis classé Gold 3 !",
            'question' => '2',
            'author' => '1',
        ],
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::RESPONSES as $i => $response) {
            $resp = (new Response())
                ->setContent($response['content'])
                ->setQuestion($this->getReference(QuestionFixtures::REFERENCE_IDENTIFIER.$response['question']))
                ->setAuthor($this->getReference(UserFixtures::REFERENCE_IDENTIFIER.$response['author']));

            $manager->persist($resp);
            $this->addReference(self::REFERENCE_IDENTIFIER.$i, $resp);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            QuestionFixtures::class,
        ];
    }

}