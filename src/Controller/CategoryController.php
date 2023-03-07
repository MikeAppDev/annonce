<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Category;
use App\Form\CategoryType;

class CategoryController extends AbstractController
{
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        // Création d'une entité "vide"
        $category = new Category();
        
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        
        // Gestion du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer l'entité qui a été modifiée
            $category = $form->getData();
            
            // Insertion de l'entité en base de données
            $entityManager = $doctrine->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            
            // Redirection
            return $this->redirectToRoute('homepage');
        }
        
        // Affichage du formulaire
        return $this->render('category/create.html.twig', [
            'form' => $form    
        ]);
    }
}