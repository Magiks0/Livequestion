<?php

namespace App\Twig;

use App\Entity\User;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'card')]
class Card
{
    public string $file;
    public string $title;
    public string $category;
    public int $responses;
    public User $author;
    public \DateTimeImmutable $createdAt;

}