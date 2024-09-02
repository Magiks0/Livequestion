<?php

namespace App\Twig\Extension;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;

final class CategoryExtension extends AbstractExtension
{

    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function getCategories(): array
    {
        return $categories = $this->em->getRepository(Category::class)->findAll();
    }

}