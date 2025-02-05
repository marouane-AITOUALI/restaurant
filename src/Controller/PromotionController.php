<?php

namespace App\Controller;

use App\Repository\PromotionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PromotionController extends AbstractController
{
    #[Route('/promotions', name: 'app_promotions')]
    public function index(PromotionRepository $promotionRepository): Response
    {
        $promotions = $promotionRepository->findAll();
        return $this->render('Promotions/index.html.twig', [
            'promotions' => $promotions,
        ]);
    }
}