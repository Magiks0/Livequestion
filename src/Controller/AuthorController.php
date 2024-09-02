<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(UserRepository $userRepository): Response
    {
        $authors = $userRepository->findAll();

        return $this->render('author/index.html.twig', [
           'authors' => $authors,
        ]);
    }
}
