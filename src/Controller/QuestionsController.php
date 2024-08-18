<?php

namespace App\Controller;

use App\Form\QuestionsType;
use App\Repository\QuestionRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Repository\CategoryRepository;

class QuestionsController extends AbstractController
{
    #[Route('/questions', name: 'app_questions')]
    public function index(
        Request $request,
        QuestionRepository $questionRepository,
        UserRepository $userRepository,
        CategoryRepository $categoryRepository,
        PaginatorInterface $paginator
    ): Response {
        // Créez le formulaire et gérez la requête
        $form = $this->createForm(QuestionsType::class);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $title = $formData->getTitle();
            $author = $formData->getAuthor();
            $category = $formData->getCategory();

            // Redirigez vers la même route avec les filtres comme paramètres de l'URL
            return $this->redirectToRoute('app_questions', [
                'title' => $title,
                'author' => $author ? $author->getId() : null,
                'category' => $category ? $category->getId() : null,
            ]);
        }

        // Récupérez les filtres à partir des paramètres de la requête
        $title = $request->query->get('title');
        $authorId = $request->query->get('author');
        $categoryId = $request->query->get('category');

        // Trouvez l'auteur et la catégorie si les IDs sont fournis
        $author = $authorId ? $userRepository->find($authorId) : null;
        $category = $categoryId ? $categoryRepository->find($categoryId) : null;

        // Appliquez les filtres si nécessaire
        $questions = $questionRepository->findQuestionsByFilters($title, $author, $category);

        // Comptez les résultats après filtrage
        $results = count($questions);

        // Appliquez la pagination
        $pagination = $paginator->paginate(
            $questions,
            $request->query->getInt('page', 1),
            10
        );

        // Rendez la vue avec les questions filtrées et paginées
        return $this->render('questions/index.html.twig', [
            'questionsForm' => $form->createView(),
            'questions' => $pagination,
            'results' => $results,
        ]);
    }



}
