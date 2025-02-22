<?php
namespace App\Controller\CrudController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('admin/events')]
final class EventsController extends AbstractController
{
    #[Route(name: 'app_events_index', methods: ['GET'])]
    #[IsGranted('ROLE_ORGANISATEUR_EVENT')]
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('events/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_events_new', methods: ['GET', 'POST'])]
    #[IsGranted('event_create')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('pathImageEvent')->getData();

            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('events_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer l'erreur si le fichier ne peut pas être déplacé
                }

                $event->setPathImageEvent('images/events/'. $newFilename);
            }

            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_events_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('events/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_events_show', methods: ['GET'])]
    #[IsGranted('event_view', subject: 'event')]
    public function show(Event $event): Response
    {
        return $this->render('events/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_events_edit', methods: ['GET', 'POST'])]
    #[IsGranted('event_edit', subject: 'event')]
    public function edit(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_events_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('events/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_events_delete', methods: ['POST'])]
    #[IsGranted('event_delete', subject: 'event')]
    public function delete(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_events_index', [], Response::HTTP_SEE_OTHER);
    }
}
