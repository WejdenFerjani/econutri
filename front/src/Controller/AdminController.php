<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\ProductRepository;
use App\Repository\OrderRepository;
use App\Repository\ReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

class AdminController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function dashboard(
        UserRepository $userRepo,
        ProductRepository $productRepo,
        OrderRepository $orderRepo,
        ReclamationRepository $reclamationRepo
    ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        // Récupérer les stats
        $totalUsers = $userRepo->count([]);
        $totalProducts = $productRepo->count([]);
        $totalOrders = $orderRepo->count([]);
        $totalReclamations = $reclamationRepo->count([]);
        
        // Produits avec stock faible (< 10)
        $lowStockProducts = $productRepo->createQueryBuilder('p')
            ->where('p.stock < 10')
            ->getQuery()
            ->getResult();
        
        // Dernières commandes (5 plus récentes)
        $recentOrders = $orderRepo->createQueryBuilder('o')
            ->orderBy('o.createdAt', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
        
        // Réclamations récentes
        $recentReclamations = $reclamationRepo->createQueryBuilder('r')
            ->orderBy('r.dateCreation', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
        
        // Tous les produits pour les graphiques
        $allProducts = $productRepo->findAll();
        
        // Préparer les données pour le graphique des quantités
        $productNames = [];
        $productStocks = [];
        foreach ($allProducts as $product) {
            $productNames[] = $product->getName();
            $productStocks[] = $product->getStock();
        }
        
        return $this->render('admin/dashboard.html.twig', [
            'totalUsers' => $totalUsers,
            'totalProducts' => $totalProducts,
            'totalOrders' => $totalOrders,
            'totalReclamations' => $totalReclamations,
            'lowStockProducts' => $lowStockProducts,
            'recentOrders' => $recentOrders,
            'recentReclamations' => $recentReclamations,
            'allProducts' => $allProducts,
            'productNames' => $productNames,
            'productStocks' => $productStocks,
        ]);
    }
    
    #[Route('/admin/users', name: 'admin_users')]
    public function users(UserRepository $userRepo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $users = $userRepo->findAll();
        
        return $this->render('admin/users.html.twig', [
            'users' => $users,
        ]);
    }
    
    #[Route('/admin/products', name: 'admin_products')]
    public function products(ProductRepository $productRepo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $products = $productRepo->findAll();
        
        return $this->render('admin/products.html.twig', [
            'products' => $products,
        ]);
    }
    
    #[Route('/admin/stocks', name: 'admin_stocks')]
    public function stocks(ProductRepository $productRepo, OrderRepository $orderRepo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $products = $productRepo->findAll();
        
        // Calculer le nombre vendu par produit
        $allOrders = $orderRepo->findAll();
        $salesByProduct = [];
        $productNames = [];
        
        foreach ($products as $product) {
            $salesByProduct[$product->getId()] = 0;
            $productNames[$product->getId()] = $product->getName();
        }
        
        foreach ($allOrders as $order) {
            foreach ($order->getOrderItems() as $orderItem) {
                $productId = $orderItem->getProduct()->getId();
                if (isset($salesByProduct[$productId])) {
                    $salesByProduct[$productId] += $orderItem->getQuantity();
                }
            }
        }
        
        // Trier par ID pour maintenir la cohérence
        ksort($salesByProduct);
        ksort($productNames);
        
        return $this->render('admin/stocks.html.twig', [
            'products' => $products,
            'salesByProduct' => $salesByProduct,
            'productNames' => $productNames,
        ]);
    }
    
    #[Route('/admin/orders', name: 'admin_orders')]
    public function orders(OrderRepository $orderRepo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $orders = $orderRepo->findAll();
        
        return $this->render('admin/orders.html.twig', [
            'orders' => $orders,
        ]);
    }
    
    #[Route('/admin/reclamations', name: 'admin_reclamations')]
    public function reclamations(ReclamationRepository $reclamationRepo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $reclamations = $reclamationRepo->findAll();
        
        return $this->render('admin/reclamations.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }
    
    #[Route('/admin/profile', name: 'admin_profile')]
    public function profile(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('admin/profile.html.twig');
    }
    
    #[Route('/admin/update-product', name: 'admin_update_product', methods: ['POST'])]
    public function updateProduct(
        Request $request,
        ProductRepository $productRepo,
        EntityManagerInterface $em
    ): JsonResponse {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $data = json_decode($request->getContent(), true);
        $productId = $data['productId'] ?? null;
        
        if (!$productId) {
            return new JsonResponse(['success' => false, 'message' => 'Produit non trouvé'], 404);
        }
        
        $product = $productRepo->find($productId);
        if (!$product) {
            return new JsonResponse(['success' => false, 'message' => 'Produit non trouvé'], 404);
        }
        
        if (isset($data['name'])) $product->setName($data['name']);
        if (isset($data['description'])) $product->setDescription($data['description']);
        if (isset($data['price'])) $product->setPrice($data['price']);
        if (isset($data['stock'])) $product->setStock((int)$data['stock']);
        
        $em->persist($product);
        $em->flush();
        
        return new JsonResponse(['success' => true, 'message' => 'Produit mis à jour']);
    }
    
    #[Route('/admin/delete-product', name: 'admin_delete_product', methods: ['POST'])]
    public function deleteProduct(
        Request $request,
        ProductRepository $productRepo,
        EntityManagerInterface $em
    ): JsonResponse {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $data = json_decode($request->getContent(), true);
        $productId = $data['productId'] ?? null;
        
        if (!$productId) {
            return new JsonResponse(['success' => false, 'message' => 'Produit non trouvé'], 404);
        }
        
        $product = $productRepo->find($productId);
        if (!$product) {
            return new JsonResponse(['success' => false, 'message' => 'Produit non trouvé'], 404);
        }
        
        $em->remove($product);
        $em->flush();
        
        return new JsonResponse(['success' => true, 'message' => 'Produit supprimé']);
    }
    
    #[Route('/admin/update-stock', name: 'admin_update_stock', methods: ['POST'])]
    public function updateStock(
        Request $request,
        ProductRepository $productRepo,
        EntityManagerInterface $em
    ): JsonResponse {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $data = json_decode($request->getContent(), true);
        $productId = $data['productId'] ?? null;
        $newStock = $data['newStock'] ?? null;
        
        if (!$productId || $newStock === null) {
            return new JsonResponse(['success' => false, 'message' => 'Données invalides'], 400);
        }
        
        $product = $productRepo->find($productId);
        if (!$product) {
            return new JsonResponse(['success' => false, 'message' => 'Produit non trouvé'], 404);
        }
        
        $product->setStock((int)$newStock);
        $em->persist($product);
        $em->flush();
        
        return new JsonResponse(['success' => true, 'message' => 'Stock mis à jour']);
    }

    #[Route('/admin/alerts', name: 'admin_alerts')]
    public function alerts(ProductRepository $productRepo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        // Produits avec stock < 10
        $lowStockProducts = $productRepo->createQueryBuilder('p')
            ->where('p.stock < 10')
            ->orderBy('p.stock', 'ASC')
            ->getQuery()
            ->getResult();
        
        // Calculer le stock critique (< 5) et le total
        $criticalCount = 0;
        $totalStock = 0;
        
        foreach ($lowStockProducts as $product) {
            if ($product->getStock() < 5) {
                $criticalCount++;
            }
            $totalStock += $product->getStock();
        }
        
        return $this->render('admin/alerts.html.twig', [
            'lowStockProducts' => $lowStockProducts,
            'alertCount' => count($lowStockProducts),
            'criticalCount' => $criticalCount,
            'totalStock' => $totalStock,
        ]);
    }
}