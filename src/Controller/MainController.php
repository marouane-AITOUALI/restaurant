<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\TableRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_hello')]
    public function index(EventRepository $eventRepository, TableRepository $tableRepository): Response
    {
        $events = $eventRepository->findAll();
        $tables = $tableRepository->findBy(['est_reserve' => 0]);
        return $this->render('Hello/hello.html.twig', [
            'events' => $events,
            'tables' => $tables,
        ]);
    }
    
    #[Route('/A-propos', name: 'a_propos')]
    public function menu(): Response
    {
        return $this->render('about/propos.html.twig');
    }
} 