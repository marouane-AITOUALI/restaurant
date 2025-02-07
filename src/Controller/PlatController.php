<?php

namespace App\Controller;

use App\Repository\PlatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Entity\Commentaire;


final class PlatController extends AbstractController
{
    #[Route('/plat/{id}', name: 'plat_detail')]
    public function index(int $id, PlatRepository $platRepository): Response
    {
        $plat = $platRepository->find($id);
        $comments = $plat->getCommentaires();
        return $this->render('plat/index.html.twig', [
            'plat' => $plat,
            'comments' => $comments,
        ]);
    }

    #[Route('/specialites', name: 'app_specialites')]
    public function specialites(PlatRepository $platRepository): Response
    {
        $platsSpecial = $platRepository->getDailySpecial();
        return $this->render('plat/specialites.html.twig', [
            'platSpecial' => $platsSpecial,
        ]);
    }

    #[Route('/plat/{id}/comment', name: 'plat_add_comment', methods: ['POST'])]
    public function addComment(
        int $id,
        Request $request,
        PlatRepository $platRepository,
        EntityManagerInterface $entityManager,
        Security $security
    ): Response {
        $user = $security->getUser();
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour commenter.');
            return $this->redirectToRoute('plat_detail', ['id' => $id]);
        }

        $plat = $platRepository->find($id);
        if (!$plat) {
            throw $this->createNotFoundException("Le plat demandé n'existe pas.");
        }

        $commentText = $request->request->get('comment');
        $rating = (int) $request->request->get('rating');

        // Validate input
        if (!$commentText || $rating < 1 || $rating > 5) {
            $this->addFlash('error', 'Veuillez remplir tous les champs correctement.');
            return $this->redirectToRoute('plat_detail', ['id' => $id]);
        }

        // Create a new Comment entity
        $comment = new Commentaire();
        $comment->setUserId($user);
        $comment->setPlat($plat);
        $comment->setComment($commentText);
        $comment->setRating($rating);

        // Save to database
        $entityManager->persist($comment);
        $entityManager->flush();

        $this->addFlash('success', 'Votre commentaire a été ajouté avec succès.');

        return $this->redirectToRoute('plat_detail', ['id' => $id]);
    }

}
