<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\QuestionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(QuestionRepository $questionRepository, UserRepository $userRepository, CategoryRepository $categoryRepository): Response
    {
        $mostResponsesQuestion = $questionRepository->findOneBy(['id' => 20]);
        $allQuestions = $questionRepository->findAll();
        $authors = $userRepository->findBy([], null, 5);
        $categories = $categoryRepository->findAll();


        return $this->render('home/index.html.twig',[
            'mostResponsesQuestion' => $mostResponsesQuestion,
            'allQuestions' => $allQuestions,
            'authors' => $authors,
            'categories' => $categories
        ]);

    }
}