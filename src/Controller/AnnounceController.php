<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\AnnounceRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Announce;

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
        
        if ($announce === false) {
            throw $this->createNotFoundException("L'annonce n'existe pas");
        }
        
        return $this->render('announce/show.html.twig', [
            'announce' => $announce,
        ]);
    }
}
