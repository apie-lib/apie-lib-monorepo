<?php
namespace Apie\AiInstructor;

use cebe\openapi\spec\Schema;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OllamaClient extends AiClient
{
    private HttpClientInterface $client;
    private string $baseUrl;

    public function __construct(?HttpClientInterface $client = null, string $baseUrl = 'http://localhost:11434')
    {
        $this->client = $client ?? HttpClient::create([]);
        $this->baseUrl = $baseUrl;
    }

    public function ask(string $systemMessage, string $prompt, Schema $schema, ?string $model = null): string
    {
        try {
            $response = $this->client->request('POST', $this->baseUrl . '/api/chat', [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => $model ?? 'tinyllama',
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
