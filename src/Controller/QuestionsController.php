<?php

namespace App\Controller;

use App\Entity\File;
use App\Entity\Question;
use App\Form\QuestionType;
use App\Form\ResearchType;
use App\Repository\CategoryRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\UX\Turbo\TurboBundle;

#[isGranted("ROLE_AUTHOR")]
class QuestionsController extends AbstractController
{
    public function __construct(EntityManagerInterface $manager)
    {
    }

    #[Route('/question', name: 'app_question')]
    public function index(Request $request, QuestionRepository $questionRepository,CategoryRepository $categoryRepository, PaginatorInterface $paginator): Response
    {
        $form = $this->createForm(ResearchType::class)->handleRequest($request);
        $newForm = clone $form;
        $questions = $questionRepository->findAll();
        $categories = $categoryRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $title = $formData->getTitle();
            $author = $formData->getAuthor();
            $category = $formData->getCategory();

            if(is_null($title) && is_null($author) && is_null($category)){
                $questions = $questionRepository->findAll();
            }else{
                $questions = $questionRepository->findQuestionsByFilters($title, $author, $category);
            }

            $pagination = $paginator->paginate(
                $questions,
                $request->query->getInt('page', 1),
                10
            );

            if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
                // If the request comes from Turbo, set the content type as text/vnd.turbo-stream.html and only send the HTML to update
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
                return $this->renderBlock('question/index.html.twig', 'filtred_questions', ['questions' => $pagination, 'questionsForm' => $newForm]);
            }

            return $this->redirectToRoute('app_questions');
        }

        $results = count($questions);

        $pagination = $paginator->paginate(
            $questions,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('question/index.html.twig', [
            'questionsForm' => $form,
            'questions' => $pagination,
            'results' => $results,
            'categories' =>$categories,
        ]);
    }

    #[Route('/question/new', name: 'app_new_question')]
    public function new(Request $request, EntityManagerInterface $manager, Security $security): Response
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class)->handleRequest($request);
        try {
            if ($form->isSubmitted() && $form->isValid()) {
                $file = (new File())
                    ->setImageFile($form->get('imageFile')->getData());
                $manager->persist($file);

                $question
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setTitle($form->getData()->getTitle())
                    ->setCategory($form->getData()->getCategory())
                    ->setAuthor($security->getUser())
                    ->setFile($file);

                $manager->persist($question);
                $this->addFlash('success', "L'exercice a bien été ajouté !");
                $manager->flush();

                return $this->redirectToRoute('app_homepage', [], Response::HTTP_SEE_OTHER);
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
            $this->addFlash('error', "Erreur pendant l'ajout de l'exercice.");
        }

        return $this->render('question/new.html.twig', [
            'form' => $form
        ]);
    }
}
