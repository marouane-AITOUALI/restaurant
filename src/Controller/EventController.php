<?php

namespace App\Controller;

use App\Repository\TableRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EventRepository;
use App\Entity\Reservation;

final class EventController extends AbstractController
{
    #[Route('/event', name: 'app_event')]
    public function index(): Response
    {
        return $this->render('event/index.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }

    #[Route('/reservation-event/{id}', name: 'event_reserve')]
    public function reservationEvent(
        Request $request,
        EventRepository $evenementRepository,
        TableRepository $tableRepository,
        EntityManagerInterface $entityManager,
    ): Response {
        $event = $evenementRepository->find($request->get('id'));

        if (!$event) {
            throw $this->createNotFoundException('Événement non trouvé.');
        }

        $user = $this->getUser();
        
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $tables = $tableRepository->findBy(['est_reserve' => 0]);
        
        if ($request->isMethod('POST')) {
            $reservation = new Reservation();
            $reservation->setUserId($user);
            $reservation->setDate(new \DateTime($request->request->get('date')));
            $reservation->setPhone($request->request->get('phone'));
            $reservation->setNumberOfPeople($request->request->get('number_of_people'));
            $reservation->setNotes($request->request->get('notes'));
            $reservation->setStatus(0); // Par défaut : en attente de validation
            $reservation->setIsEvent(1);
            $table = $tableRepository->find($request->request->get('table_id'));
            $reservation->setTableId($table);
            $table->setEstReserve(1);

            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('event_success');
        }

        return $this->render('event/index.html.twig', [
            'event' => $event,
            'tables' => $tables,
        ]);
    }


    #[Route('/reservation-success', name: 'event_success')]
    public function success(): Response
    {
        return $this->render('event/success.html.twig');
    }

}
