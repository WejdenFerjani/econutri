<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class SpoonacularClient
{
    private HttpClientInterface $httpClient;
    private string $apiKey;

    public function __construct(HttpClientInterface $httpClient, string $apiKey)
    {
        $this->httpClient = $httpClient;
        $this->apiKey = $apiKey;
    }

    /**
     * Search healthy recipes with optional constraints
     * @param string $query
     * @param array $options [diet?, intolerances?, maxReadyTime?, number?]
     * @return array
     */
    public function searchHealthyRecipes(string $query, array $options = []): array
    {
        $params = [
            'query' => $query,
            'addRecipeInformation' => 'true',
            'addRecipeNutrition' => 'true',
            'instructionsRequired' => 'true',
            'number' => $options['number'] ?? 5,
        ];
        if (!empty($options['diet'])) {
            $params['diet'] = $options['diet'];
        }
        if (!empty($options['intolerances'])) {
            $params['intolerances'] = $options['intolerances'];
        }
        if (!empty($options['maxReadyTime'])) {
            $params['maxReadyTime'] = (int)$options['maxReadyTime'];
        }

        $url = 'https://api.spoonacular.com/recipes/complexSearch';
        $response = $this->httpClient->request('GET', $url, [
            'query' => array_merge($params, [ 'apiKey' => $this->apiKey ]),
            'timeout' => 10,
        ]);

        $data = $response->toArray(false);
        $results = $data['results'] ?? [];

        // Post-filter to ensure healthy criteria
        $healthy = [];
        foreach ($results as $r) {
            $nutrition = $r['nutrition']['nutrients'] ?? [];
            $cal = self::findNutrient($nutrition, 'Calories');
            $prot = self::findNutrient($nutrition, 'Protein');
            $sugar = self::findNutrient($nutrition, 'Sugar');
            $readyIn = $r['readyInMinutes'] ?? null;

            if (
                ($cal === null || $cal['amount'] <= 600) &&
                ($prot === null || $prot['amount'] >= 20) &&
                ($sugar === null || $sugar['amount'] <= 15) &&
                ($readyIn === null || $readyIn <= ($options['maxReadyTime'] ?? 30))
            ) {
                $healthy[] = [
                    'id' => $r['id'],
                    'title' => $r['title'] ?? 'Recette',
                    'image' => $r['image'] ?? null,
                    'readyInMinutes' => $readyIn,
                    'calories' => $cal['amount'] ?? null,
                    'protein' => $prot['amount'] ?? null,
                    'sugar' => $sugar['amount'] ?? null,
                    'sourceUrl' => $r['sourceUrl'] ?? null,
                ];
            }
        }

        return array_slice($healthy, 0, (int)($options['number'] ?? 5));
    }

    private static function findNutrient(array $nutrients, string $name): ?array
    {
        foreach ($nutrients as $n) {
            if (($n['name'] ?? '') === $name) {
                return $n;
            }
        }
        return null;
    }
}
