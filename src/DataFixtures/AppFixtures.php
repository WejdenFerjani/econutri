<?php
// src/DataFixtures/AppFixtures.php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Création des catégories
        $categories = [
            'Légumes' => 'Nos légumes bio frais et locaux',
            'Fruits' => 'Fruits biologiques de saison', 
            'Laitiers' => 'Produits laitiers bio',
            'Grains' => 'Grains et céréales biologiques'
        ];

        $categoryObjects = [];
        
        foreach ($categories as $name => $description) {
            $category = new Category();
            $category->setName($name);
            $category->setDescription($description);
            $category->setImage(strtolower($name) . '.jpg');
            $manager->persist($category);
            $categoryObjects[$name] = $category;
        }

        // Création de produits
        $products = [
            ['Tomates Bio', 'Tomates rouges biologiques fraîches', 4.50, 'tomates.jpg', 50, 'Légumes'],
            ['Carottes Bio', 'Carottes fraîches biologiques', 3.20, 'carottes.jpg', 30, 'Légumes'],
            ['Pommes Golden', 'Pommes golden biologiques', 2.80, 'pommes.jpg', 40, 'Fruits'],
            ['Lait Bio', 'Lait entier biologique 1L', 1.50, 'lait.jpg', 20, 'Laitiers'],
            ['Riz Complet Bio', 'Riz complet biologique 1kg', 5.00, 'riz.jpg', 25, 'Grains'],
            ['Bananes Bio', 'Bananes biologiques', 3.50, 'bananes.jpg', 35, 'Fruits'],
            ['Fromage Bio', 'Fromage de chèvre biologique', 6.80, 'fromage.jpg', 15, 'Laitiers'],
        ];

        foreach ($products as $productData) {
            $product = new Product();
            $product->setName($productData[0]);
            $product->setDescription($productData[1]);
            $product->setPrice($productData[2]);
            $product->setImage($productData[3]);
            $product->setStock($productData[4]);
            $product->setCategory($categoryObjects[$productData[5]]);
            $manager->persist($product);
        }

        $manager->flush();
    }
}