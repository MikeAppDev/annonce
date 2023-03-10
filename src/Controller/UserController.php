<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UpdateProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/user', name: 'user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            $user = $this->getUser(),
        ]);
    }
    #[Route('/profile', name: 'profile')]
    public function profile(): Response
    {
        return $this->render('user/index.html.twig', [
            $user = $this->getUser(),
        ]);
    }
    
    #[Route('/edit_profile/{id}', name: 'edit_profile')]
    public function edit_profile(User $user = null, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        
        if(!$user)
        {
            $user = new User();
        }

        $form = $this->createForm(UpdateProfileFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            if(!$user->getId())
                $mot = 'Enregistré';
            else
                $mot = 'Modifier';

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('succes', "Changement : $mot avec succès");
            // do anything else you need here, like send an email

            return $this->redirectToRoute('profile',[
                'id' => $user->getId()
            ]);
        }

        return $this->render('user/form.html.twig', [
            $user = $this->getUser(),
            'form' => $form->createView(),
            'editMode' => $user->getId(),
        ]);
    }
}
