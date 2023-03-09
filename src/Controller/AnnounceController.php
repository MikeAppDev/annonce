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
use App\Entity\Picture;
use App\Form\AnnounceType;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AnnounceController extends AbstractController
{
    #[Route('/announces', name: 'announces')]
    public function index(AnnounceRepository $repository): Response
    {
        $announces = $repository->findAllByDesc();

        return $this->render('announce/index.html.twig', [
            'announces' => $announces    
        ]);
    }

    #[Route('/announces/show/{slug}', name: 'announces/{slug}')]
    public function show(string $slug, AnnounceRepository $repository, EntityManagerInterface $doctrine): Response
    {
        //$entityManager = $doctrine->getManager();
        //$announce = $doctrine->getRepository(Announce::class)->find($id);
        $announce = $repository->findOneBy(['slug' => $slug]);

        if (!$announce) {
            throw $this->createNotFoundException("L'annonce n'existe pas");
        }
        
        return $this->render('announce/show.html.twig', [
            'announce' => $announce,
            'slug' => $announce->getSlug(),
        ]);
    }

    #[Route('/announces/create', name: 'announces/create')]
    public function create(Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger, Announce $announce = null): Response
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
             //gestion de l'image
             $imageFiles = $form->get('picture')->getData();
             //  dd($announce->getPicture());
             
            $entityManager = $doctrine->getManager();

            foreach($imageFiles as $imageFile){
            if ($imageFile) {
                $originalFileName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFileName);
                $newFilename =  $safeFilename.'-'.uniqid().".".$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('upload_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('message','une erreur est survenu lors de l\'upload de l\'image!');
                    // return $this->redirectToRoute('allbuild');
                }
            }
            $picture = new Picture();
            $picture->setPath($newFilename);
            $entityManager->persist($picture);
            $announce->addPicture($picture);
            }
             // Récupérer l'entité qui a été modifiée
             $announce = $form->getData();
             $announce->setCreatedAt(new \DateTime());
             $announce->setupdatedAt(new \DateTime());
             $announce->setSlug($announce->getTitle());
             
             // Insertion de l'entité en base de données
             $entityManager->persist($announce);
             $entityManager->flush();
             
             // Redirection
             return $this->redirectToRoute('announces.index');
         }

         // Affichage du formulaire
         return $this->render('announce/create.html.twig', [
             'form' => $form,
             'user' => $user,
         ]);
    }
    
    
    public function edit(string $slug, ManagerRegistry $doctrine, EntityManagerInterface $manager, Request $request, SluggerInterface $slugger, AnnounceRepository $repository): Response
    {
        $announce = $repository->findOneBy(['slug' => $slug]);

        // Si l'article n'existe pas on renvoie une page 404
        if (!$announce) {
            throw $this->createNotFoundException("L'article n'existe pas");
        }

        // Si l'auteur de l'article n'est pas le même que l'utilisateur connecté
        // On n'autorise pas l'accès (sauf si l'utilisateur est administrateur)
        // if ($post->getUser() !== $this->getUser()) {
        //     $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // }
        
        // Utilisation du voter qui gère l'édition de l'article
        $this->denyAccessUnlessGranted('ANNOUNCE_EDIT', $announce);

        $form = $this->createForm(AnnounceType::class, $announce, [
            'edit_mode' => true
        ]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $imageFiles = $form->get('picture')->getData();
            //  dd($announce->getPicture());
            
           $entityManager = $doctrine->getManager();

           foreach($imageFiles as $imageFile){
           if ($imageFile) {
               $originalFileName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
               $safeFilename = $slugger->slug($originalFileName);
               $newFilename =  $safeFilename.'-'.uniqid().".".$imageFile->guessExtension();

               try {
                   $imageFile->move(
                       $this->getParameter('upload_directory'),
                       $newFilename
                   );
               } catch (FileException $e) {
                   $this->addFlash('message','une erreur est survenu lors de l\'upload de l\'image!');
                   // return $this->redirectToRoute('allbuild');
               }
           }
           $picture = new Picture();
           $picture->setPath($newFilename);
           $entityManager->persist($picture);
           $announce->addPicture($picture);
           }


            $announce->setSlug($slugger->slug($announce->getTitle()));
            $announce->setUpdatedAt(new \DateTimeImmutable());
            $manager->persist($announce);
            $manager->flush();

            return $this->redirectToRoute('announces.index');
        }

        return $this->renderForm('announce/edit.html.twig', [
            'form' => $form
        ]);
    }


    public function delete(int $id, EntityManagerInterface $manager): Response
    {
        
        // Attention pour ne pas avoir de problèmes avec les commentaires (contrainte de clé étrangère)
        // Il faut penser à rajouter ceci dans l'entité Comment : #[ORM\JoinColumn(onDelete: 'CASCADE')]
        
        $announce = $manager->getReference(Announce::class, $id);
        $this->denyAccessUnlessGranted('ANNOUNCE_DELETE', $announce);
        $manager->remove($announce);
        $manager->flush();

        return $this->redirectToRoute('announces.index');
    }
}
