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

        $date = $request->request->get('date');
        $time = $request->request->get('time');
        $persons = $request->request->get('persons');
        $tableId = $request->request->get('table_id');
        $phone = $request->request->get('phone');
        $notes = $request->request->get('notes');

  
        $table = $tableRepository->find($tableId);

        $reservation = new Reservation();
        $reservation->setUserId($this->getUser()); // Assure que user est connecté
        $reservation->setDate(new \DateTime($date . ' ' . $time)); 
        $reservation->setNumberOfPeople($persons);
        $reservation->setTableId($table);
        $reservation->setStatus(0); 
        $reservation->setIsEvent(0); 
        $reservation->setPhone($phone);
        $reservation->setNotes($notes);
        $table->setEstReserve(1);

        $em->persist($reservation);
        $em->persist($table);
        $em->flush();

        return $this->redirectToRoute('app_reservations_user');
    }

    public function manageReservationByAdmin(ReservationRepository $reservationRepository, $id_reservation, $etat): Response
    {
        $reservation = $reservationRepository->find($id_reservation);
        $reservation->setStatus($etat);
        return $this->redirectToRoute('admin_dashboard');
    }
}
