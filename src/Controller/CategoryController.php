<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/categorie/{slug}', name: 'app_category')]
    public function index(string $slug): Response
    {
        // Données des catégories
        $categories = [
            'legumes' => [
                'name' => 'Légumes Bio',
                'description' => 'Découvrez nos légumes biologiques frais et locaux, cultivés avec amour et respect de la nature.',
                'image' => 'legumes.jpg'
            ],
            'fruits' => [
                'name' => 'Fruits Bio', 
                'description' => 'Des fruits savoureux et nutritifs, récoltés à maturité pour préserver toutes leurs qualités.',
                'image' => 'fruits.jpg'
            ],
            'laitiers' => [
                'name' => 'Produits Laitiers',
                'description' => 'Fromages, laits et yaourts bio issus de fermes locales qui respectent le bien-être animal.',
                'image' => 'laitiers.jpg'
            ],
            'grains' => [
                'name' => 'Grains & Céréales',
                'description' => 'Céréales complètes et légumineuses bio pour une alimentation saine et équilibrée.',
                'image' => 'grains.jpg'
            ]
        ];

        // Données des produits par catégorie
        $productsData = [
            'legumes' => [
                [
                    'name' => 'Tomates Bio',
                    'slug' => 'tomates-bio',
                    'price' => 3.50,
                    'image' => 'tomates.jpg',
                    'origin' => 'Local',
                    'stock' => 25,
                    'unit' => 'kg',
                    'description' => 'Tomates juteuses et savoureuses cultivées en plein air.'
                ],
                [
                    'name' => 'Carottes Bio',
                    'slug' => 'carottes-bio', 
                    'price' => 2.80,
                    'image' => 'carottes.jpg',
                    'origin' => 'France',
                    'stock' => 18,
                    'unit' => 'kg',
                    'description' => 'Carottes croquantes et sucrées, riches en bêta-carotène.'
                ]
            ],
            'fruits' => [
                [
                    'name' => 'Pommes Golden',
                    'slug' => 'pommes-golden',
                    'price' => 2.90,
                    'image' => 'pommes.jpg',
                    'origin' => 'Local', 
                    'stock' => 30,
                    'unit' => 'kg',
                    'description' => 'Pommes sucrées et parfumées, parfaites pour la cuisson.'
                ],
                [
                    'name' => 'Bananes Bio',
                    'slug' => 'bananes-bio',
                    'price' => 1.90,
                    'image' => 'bananes.jpg',
                    'origin' => 'Amérique du Sud',
                    'stock' => 15,
                    'unit' => 'kg',
                    'description' => 'Bananes équitables, riches en potassium et énergie.'
                ]
            ],
            'laitiers' => [
                [
                    'name' => 'Lait Bio Entier',
                    'slug' => 'lait-bio-entier',
                    'price' => 1.20,
                    'image' => 'lait.jpg',
                    'origin' => 'Local',
                    'stock' => 20,
                    'unit' => 'L',
                    'description' => 'Lait cru entier de vaches élevées en plein air.'
                ],
                [
                    'name' => 'Fromage de Chèvre',
                    'slug' => 'fromage-chevre',
                    'price' => 4.50,
                    'image' => 'fromage.jpg', 
                    'origin' => 'France',
                    'stock' => 12,
                    'unit' => 'pièce',
                    'description' => 'Fromage de chèvre crémeux et onctueux.'
                ]
            ],
            'grains' => [
                [
                    'name' => 'Riz Complet Bio',
                    'slug' => 'riz-complet-bio',
                    'price' => 3.20,
                    'image' => 'riz.jpg',
                    'origin' => 'Italie',
                    'stock' => 25,
                    'unit' => 'kg',
                    'description' => 'Riz complet biologique, riche en fibres et nutriments.'
                ]
            ]
        ];

        $category = $categories[$slug] ?? null;
        $products = $productsData[$slug] ?? [];

        if (!$category) {
            throw $this->createNotFoundException('Catégorie non trouvée');
        }

        return $this->render('category.html.twig', [
            'category' => $category,
            'products' => $products
        ]);
    }
}