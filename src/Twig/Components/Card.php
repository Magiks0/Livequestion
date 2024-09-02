<?php

namespace App\Twig\Components;

use App\Entity\File;
use App\Entity\User;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'card')]
class Card
{
    public File $file;
    public string $title;
    public string $category;
    public int $responses;
    public User $author;
    public \DateTimeImmutable $createdAt;

}