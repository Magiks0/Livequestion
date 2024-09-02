<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class RegisterController extends AbstractController
{
    public function __construct(
        private readonly AuthenticationUtils $authenticationUtils,
        private readonly EntityManagerInterface $manager
    ){
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordEncoder) :Response
    {
        $error = $this->authenticationUtils->getLastAuthenticationError();

        $form = $this->createForm(RegisterType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $user = new User();
            $user->setUsername($formData['username'])
                ->setEmail($formData['email'])
                ->setPassword($passwordEncoder->hashPassword($user, $formData['password']));

            $this->manager->persist($user);
            $this->manager->flush();
        }


        return $this->render('login/index.html.twig', [
            'form' => $form,
            'error' => $error,
        ]);
    }
}
