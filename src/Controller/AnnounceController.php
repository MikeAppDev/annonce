<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\AnnounceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Announce;
use App\Form\AnnounceType;

class AnnounceController extends AbstractController
{
    #[Route('/announces', name: 'announces')]
    public function index(AnnounceRepository $repository): Response
    {
        $announces = $repository->findAll();

        return $this->render('announce/index.html.twig', [
            'announces' => $announces    
        ]);
    }

    #[Route('/announces/show/{id}', name: 'announces/{id}')]
    public function show(Announce $announce, EntityManagerInterface $doctrine): Response
    {
        //$entityManager = $doctrine->getManager();
        //$announce = $doctrine->getRepository(Announce::class)->find($id);
        if (!$announce) {
            throw $this->createNotFoundException("L'annonce n'existe pas");
        }
        
        return $this->render('announce/show.html.twig', [
            'announce' => $announce,
            'id' => $announce->getId(),
        ]);
    }

    #[Route('/announces/create', name: 'announces/create')]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
         // Création d'une entité "vide"
         $announce = new Announce();
        
         $form = $this->createForm(AnnounceType::class, $announce);
         $form->handleRequest($request);

         $user = $this->getUser();
         $announce->setAuthor($user);
         
         // Gestion du formulaire
         if ($form->isSubmitted() && $form->isValid()) {
             // Récupérer l'entité qui a été modifiée
             $announce = $form->getData();
             $announce->setCreatedAt(new \DateTime());
             $announce->setupdatedAt(new \DateTime());
             // Insertion de l'entité en base de données
             $entityManager = $doctrine->getManager();
             $entityManager->persist($announce);
             $entityManager->flush();
             
             // Redirection
             return $this->redirectToRoute('announces.index');
         }
         
         // Affichage du formulaire
         return $this->render('announce/create.html.twig', [
             'form' => $form,
             'user' => $user
         ]);
    }
    
}
