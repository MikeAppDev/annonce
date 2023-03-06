<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    public function index(): Response
    {
        // Affichage de la vue
        return $this->render('default/homepage.html.twig');
    }
}