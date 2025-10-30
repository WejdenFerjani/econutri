<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/produit/{slug}', name: 'app_product')]
    public function index(string $slug): Response
    {
        // Données des produits
        $products = [
            'tomates-bio' => [
                'name' => 'Tomates Bio',
                'slug' => 'tomates-bio',
                'price' => 3.50,
                'image' => 'tomates.jpg',
                'category' => ['name' => 'Légumes Bio', 'slug' => 'legumes'],
                'origin' => 'Local',
                'stock' => 25,
                'unit' => 'kg',
                'season' => 'Été',
                'description' => 'Tomates juteuses et savoureuses cultivées en plein air.',
                'fullDescription' => 'Nos tomates biologiques sont cultivées avec amour dans nos serres locales. Récoltées à maturité, elles offrent une chair ferme et un goût sucré exceptionnel. Parfaites pour vos salades, sauces et plats cuisinés.',
                'nutrition' => [
                    'calories' => 18,
                    'proteins' => 0.9,
                    'carbs' => 3.9,
                    'fiber' => 1.2
                ]
            ],
            'carottes-bio' => [
                'name' => 'Carottes Bio',
                'slug' => 'carottes-bio',
                'price' => 2.80,
                'image' => 'carottes.jpg',
                'category' => ['name' => 'Légumes Bio', 'slug' => 'legumes'],
                'origin' => 'France',
                'stock' => 18,
                'unit' => 'kg',
                'season' => 'Toute l\'année',
                'description' => 'Carottes croquantes et sucrées, riches en bêta-carotène.',
                'fullDescription' => 'Nos carottes biologiques sont cultivées en pleine terre, ce qui leur confère une saveur sucrée et une texture croquante exceptionnelles. Riches en bêta-carotène et en vitamines, elles sont excellentes pour la santé oculaire et cutanée.',
                'nutrition' => [
                    'calories' => 41,
                    'proteins' => 0.9,
                    'carbs' => 10,
                    'fiber' => 2.8
                ]
            ],
            'pommes-golden' => [
                'name' => 'Pommes Golden',
                'slug' => 'pommes-golden',
                'price' => 2.90,
                'image' => 'pommes.jpg',
                'category' => ['name' => 'Fruits Bio', 'slug' => 'fruits'],
                'origin' => 'Local',
                'stock' => 30,
                'unit' => 'kg',
                'season' => 'Automne',
                'description' => 'Pommes sucrées et parfumées, parfaites pour la cuisson.',
                'fullDescription' => 'Les pommes Golden biologiques sont réputées pour leur chair ferme et leur goût sucré délicat. Cultivées dans nos vergers locaux, elles sont parfaites pour être consommées crues ou cuites dans vos desserts et plats salés.',
                'nutrition' => [
                    'calories' => 52,
                    'proteins' => 0.3,
                    'carbs' => 14,
                    'fiber' => 2.4
                ]
            ],
            'bananes-bio' => [
                'name' => 'Bananes Bio',
                'slug' => 'bananes-bio',
                'price' => 1.90,
                'image' => 'bananes.jpg',
                'category' => ['name' => 'Fruits Bio', 'slug' => 'fruits'],
                'origin' => 'Amérique du Sud',
                'stock' => 15,
                'unit' => 'kg',
                'season' => 'Toute l\'année',
                'description' => 'Bananes équitables, riches en potassium et énergie.',
                'fullDescription' => 'Nos bananes biologiques et équitables proviennent de coopératives sud-américaines qui respectent les travailleurs et l\'environnement. Riches en potassium et en glucides naturels, elles sont une excellente source d\'énergie.',
                'nutrition' => [
                    'calories' => 89,
                    'proteins' => 1.1,
                    'carbs' => 23,
                    'fiber' => 2.6
                ]
            ],
            'lait-bio-entier' => [
                'name' => 'Lait Bio Entier',
                'slug' => 'lait-bio-entier',
                'price' => 1.20,
                'image' => 'lait.jpg',
                'category' => ['name' => 'Produits Laitiers', 'slug' => 'laitiers'],
                'origin' => 'Local',
                'stock' => 20,
                'unit' => 'L',
                'season' => 'Toute l\'année',
                'description' => 'Lait cru entier de vaches élevées en plein air.',
                'fullDescription' => 'Notre lait biologique entier provient de vaches élevées en plein air dans des fermes locales. Non homogénéisé et pasteurisé basse température, il conserve toutes ses qualités nutritionnelles et son goût authentique.',
                'nutrition' => [
                    'calories' => 61,
                    'proteins' => 3.2,
                    'carbs' => 4.8,
                    'fiber' => 0
                ]
            ],
            'fromage-chevre' => [
                'name' => 'Fromage de Chèvre',
                'slug' => 'fromage-chevre',
                'price' => 4.50,
                'image' => 'fromage.jpg',
                'category' => ['name' => 'Produits Laitiers', 'slug' => 'laitiers'],
                'origin' => 'France',
                'stock' => 12,
                'unit' => 'pièce',
                'season' => 'Printemps',
                'description' => 'Fromage de chèvre crémeux et onctueux.',
                'fullDescription' => 'Ce fromage de chèvre biologique est fabriqué artisanalement dans une fromagerie française. Sa texture crémeuse et son goût délicat en font un fromage d\'exception, parfait pour l\'apéritif ou en salade.',
                'nutrition' => [
                    'calories' => 110,
                    'proteins' => 9,
                    'carbs' => 2,
                    'fiber' => 0
                ]
            ],
            'riz-complet-bio' => [
                'name' => 'Riz Complet Bio',
                'slug' => 'riz-complet-bio',
                'price' => 3.20,
                'image' => 'riz.jpg',
                'category' => ['name' => 'Grains & Céréales', 'slug' => 'grains'],
                'origin' => 'Italie',
                'stock' => 25,
                'unit' => 'kg',
                'season' => 'Toute l\'année',
                'description' => 'Riz complet biologique, riche en fibres et nutriments.',
                'fullDescription' => 'Notre riz complet biologique est cultivé en Italie dans le respect de l\'environnement. Le riz complet conserve son son et son germe, ce qui le rend plus riche en fibres, vitamines et minéraux que le riz blanc.',
                'nutrition' => [
                    'calories' => 111,
                    'proteins' => 2.6,
                    'carbs' => 23,
                    'fiber' => 1.8
                ]
            ]
        ];

        $product = $products[$slug] ?? null;

        if (!$product) {
            throw $this->createNotFoundException('Produit non trouvé');
        }

        // Produits similaires (même catégorie)
        $relatedProducts = array_filter($products, function($p) use ($product) {
            return $p['category']['slug'] === $product['category']['slug'] && $p['slug'] !== $product['slug'];
        });

        // Limiter à 4 produits maximum
        $relatedProducts = array_slice($relatedProducts, 0, 4);

        return $this->render('product.html.twig', [
            'product' => $product,
            'relatedProducts' => $relatedProducts
        ]);
    }
}