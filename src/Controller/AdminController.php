<?php

namespace App\Controller;

use App\Repository\PlatRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'app_admin')]
    public function index(UserRepository $userRepository, PlatRepository $platRepository, ReservationRepository $reservationRepository): Response
    {
        $reservations = $reservationRepository->findAll();
        $users = $userRepository->findAll();
        $plats = $platRepository->findAll();
        return $this->render('admin/index.html.twig', [
            'users' => $users,
            'plats' => $plats,
            'reservations' => $reservations,
        ]);
    }
}
