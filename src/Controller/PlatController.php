<?php

namespace App\Controller;

use App\Repository\PlatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
}
