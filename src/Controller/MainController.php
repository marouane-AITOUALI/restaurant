<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_hello')]
    public function index(): Response
    {
        return $this->render('Hello/hello.html.twig', [
            'message' => 'Bienvenue sur KoulMaghreb!'
        ]);
    }
    #[Route('/register', name: 'app_register')]
    public function register(): Response
    {
        return $this->render('Registration/inscription.html.twig');
    }
    #[Route('/login', name: 'app_connexion')]
    public function connect(): Response
    {
        return $this->render('Registration/connexion.html.twig');
    }
} 