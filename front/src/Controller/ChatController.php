<?php

namespace App\Controller;

use App\Service\SpoonacularClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ChatController extends AbstractController
{
    #[Route('/api/chat/recipes', name: 'api_chat_recipes', methods: ['POST'])]
    public function recipes(Request $request, SpoonacularClient $client): JsonResponse
    {
        $payload = json_decode($request->getContent(), true) ?? [];
        $query = trim((string)($payload['query'] ?? 'healthy'));
        $diet = $payload['diet'] ?? null; // vegetarian|vegan|gluten free
        $intolerances = $payload['intolerances'] ?? null; // comma-separated
        $maxReadyTime = (int)($payload['maxReadyTime'] ?? 30);
        $number = (int)($payload['number'] ?? 5);

        try {
            $recipes = $client->searchHealthyRecipes($query, [
                'diet' => $diet,
                'intolerances' => $intolerances,
                'maxReadyTime' => $maxReadyTime,
                'number' => $number,
            ]);
            return new JsonResponse(['success' => true, 'recipes' => $recipes]);
        } catch (\Throwable $e) {
            return new JsonResponse(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
