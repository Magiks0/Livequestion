<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public const REFERENCE_IDENTIFIER = 'category_';

    public const CATEGORIES = [
        'Musique',
        'Business',
        'Sports',
        'Films',
        'Politique',
        'Jeux Videos',
        'Santé',
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::CATEGORIES as $i => $category) {
            $cate = (new Category())
            ->setName($category);

            $manager->persist($cate);
            $this->addReference(self::REFERENCE_IDENTIFIER . $i, $cate);
        }
        $manager->flush();
    }
}
