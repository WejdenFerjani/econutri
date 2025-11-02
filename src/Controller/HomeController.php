<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $productRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Récupérer les produits recommandés (6 mieux stockés) et nouveaux (3 plus récents)
        $recommendedProducts = $productRepository->findRecommendedProducts(6);
        $newProducts = $productRepository->findNewProducts(3);

        return $this->render('home/index.html.twig', [
            'recommendedProducts' => $recommendedProducts,
            'newProducts' => $newProducts,
        ]);
    }
}