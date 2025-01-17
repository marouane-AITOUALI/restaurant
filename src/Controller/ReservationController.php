<?php

namespace App\Controller;

use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
final class ReservationController extends AbstractController
{
    #[Route('/reservations', name: 'app_reservations')]
    public function index(ReservationRepository $reservationRepository): Response
    {
        $user = $this->getUser();
        $reservations = $reservationRepository->findBy(['user_id' => $user]);
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }
}
