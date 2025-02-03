<?php

namespace App\Controller;

use App\Repository\ReservationRepository;
use App\Repository\TableRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Reservation;
final class ReservationController extends AbstractController
{
    #[Route('/reservations', name: 'app_reservations_user')]
    public function index(ReservationRepository $reservationRepository): Response
    {
        $user = $this->getUser();
        $reservations = $reservationRepository->findBy(['user_id' => $user]);
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    #[Route('/reservations/create', name: 'reservation_create', methods: ['POST'])]
    public function create(Request $request, TableRepository $tableRepository, EntityManagerInterface $em): Response
    {
        // Récupérer les données du formulaire
        $date = $request->request->get('date');
        $time = $request->request->get('time');
        $persons = $request->request->get('persons');
        $tableId = $request->request->get('table_id');
        $phone = $request->request->get('phone');
        $notes = $request->request->get('notes');

        // Récupérer la table sélectionnée
        $table = $tableRepository->find($tableId);

        // Créer la réservation
        $reservation = new Reservation();
        $reservation->setUserId($this->getUser()); // Assurer que l'utilisateur est connecté
        $reservation->setDate(new \DateTime($date . ' ' . $time)); // Créer un objet DateTime
        $reservation->setNumberOfPeople($persons);
        $reservation->setTableId($table);
        $reservation->setStatus(0); // Statut initial
        $reservation->setIsEvent(0); // Pas un événement
        $reservation->setPhone($phone);
        $reservation->setNotes($notes);
        // Marquer la table comme réservée
        $table->setEstReserve(1);

        // Enregistrer la réservation
        $em->persist($reservation);
        $em->persist($table);
        $em->flush();

        // Retourner une réponse, ici une redirection vers la page des réservations
        return $this->redirectToRoute('app_reservations');
    }

    public function manageReservationByAdmin(ReservationRepository $reservationRepository, $id_reservation, $etat): Response
    {
        $reservation = $reservationRepository->find($id_reservation);
        $reservation->setStatus($etat);
        return $this->redirectToRoute('admin_dashboard');
    }
}
