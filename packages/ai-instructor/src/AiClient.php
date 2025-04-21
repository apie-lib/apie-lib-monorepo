<?php
namespace Apie\AiInstructor;

use cebe\openapi\spec\Schema;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AiClient
{
    private HttpClientInterface $client;
    private string $apiKey;
    private string $baseUrl;

    public function __construct(?HttpClientInterface $client = null, string $apiKey = 'ignored', string $baseUrl = 'https://api.openai.com/v1')
    {
        $this->client = $client ?? HttpClient::create([]);
        $this->apiKey = $apiKey;
        $this->baseUrl = $baseUrl;
    }

    public function ask(string $systemMessage, string $prompt, Schema $schema, string $model = 'gpt-4'): string
    {
        try {
            $response = $this->client->request('POST', $this->baseUrl . '/api/chat', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => $model,
                    'stream' => false,
                    'format' => $schema->getSerializableData(),
                    'messages' => [
                        ['role' => 'system', 'content' => $systemMessage],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                ],
            ]);

            $data = $response->toArray();

            return $data['message']['content'] ?? 'No response';
        } catch (TransportExceptionInterface $e) {
            return 'Request failed: ' . $e->getMessage();
        }
    }
}
