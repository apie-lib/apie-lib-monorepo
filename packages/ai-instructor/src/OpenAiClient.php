<?php
namespace Apie\AiInstructor;

use cebe\openapi\spec\Schema;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OpenAiClient extends AiClient
{
    private HttpClientInterface $client;
    private string $apiKey;

    public function __construct(?HttpClientInterface $client = null, string $apiKey = 'ignored')
    {
        $this->client = $client ?? HttpClient::create([]);
        $this->apiKey = $apiKey;
    }

    public function ask(string $systemMessage, string $prompt, Schema $schema, ?string $model = null): string
    {
        try {
            $response = $this->client->request('POST', 'https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => $model ?? 'gpt4-mini',
                    'messages' => [
                        ['role' => 'system', 'content' => $systemMessage],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'functions' => [[
                        'name' => 'structured_response',
                        'description' => 'Structured response as defined by schema',
                        'parameters' => $schema->getSerializableData(),
                    ]],
                    'function_call' => ['name' => 'structured_response'],
                ],
            ]);

            $data = $response->toArray();

            $functionCall = $data['choices'][0]['message']['function_call']['arguments'] ?? null;

            return $functionCall ? json_encode(json_decode($functionCall, true), JSON_PRETTY_PRINT) : 'No structured response';
        } catch (TransportExceptionInterface $e) {
            return 'Request failed: ' . $e->getMessage();
        }
    }
}
