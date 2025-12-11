<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ProxyController extends AbstractController
{
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    #[Route('/proxy/chat', name: 'proxy_chat', methods: ['POST'])]
    public function proxyChat(Request $request): Response
    {
        $n8nWebhookUrl = 'https://maryem1.app.n8n.cloud/webhook/econutri-chat';

        // Forward the request to the n8n webhook
        $response = $this->httpClient->request(
            'POST',
            $n8nWebhookUrl,
            [
                'json' => json_decode($request->getContent(), true),
            ]
        );

        return new Response(
            $response->getContent(),
            $response->getStatusCode(),
            ['Content-Type' => 'application/json']
        );
    }
}
