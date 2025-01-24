<?php

namespace App\Controller;

use App\Repository\PlatRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
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

    #[Route('/reservations/gestion_admin_reservation/{id}/{etat}', name: 'reservation_manage', methods: ['GET', 'POST'])]
    public function manageReservationByAdmin(ReservationRepository $reservationRepository, EntityManagerInterface $entityManager, $id, $etat): Response
    {
        $reservation = $reservationRepository->find($id);

        if ($reservation) {
            $reservation->setStatus($etat);
            $entityManager->flush();
            $this->addFlash('success', 'Réservation mise à jour');
        } else {
            $this->addFlash('error', 'Réservation non trouvée');
        }

        return $this->redirectToRoute('app_admin');
    }

}
