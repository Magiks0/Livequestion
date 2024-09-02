<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\QuestionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[isGranted("ROLE_AUTHOR")]
class HomeController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(QuestionRepository $questionRepository, UserRepository $userRepository, CategoryRepository $categoryRepository): Response
    {
        $mostResponsesQuestion = $questionRepository->findMostRespondedQuestion();

        $lastQuestions = $questionRepository->findBy([], ['createdAt' => 'DESC'], 3);

        $questions = $questionRepository->findAll();
        shuffle($questions);
        $randomQuestions = array_slice($questions, 0, 3);

        $sportsCategory = $categoryRepository->findOneBy(['name' => 'Sports']);
        $sportsQuestions = $questionRepository->findBy(['category' => $sportsCategory], [], 3);

        $bestAuthors = $userRepository->findBestAuthors();

        $categories = $categoryRepository->findAll();


        return $this->render('home/index.html.twig',[
            'mostResponsesQuestion' => $mostResponsesQuestion,
            'lastQuestions' => $lastQuestions,
            'randomQuestions' => $randomQuestions,
            'authors' => $bestAuthors,
            'categories' => $categories,
            'sportsQuestions' => $sportsQuestions,
        ]);

    }
}