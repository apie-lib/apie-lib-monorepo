<?php
namespace Apie\AiInstructor;

use cebe\openapi\spec\Schema;
use SensitiveParameter;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OpenAiClient extends AiClient
{
    private HttpClientInterface $client;
    private string $apiKey;

    public function __construct(?HttpClientInterface $client = null, #[SensitiveParameter] string $apiKey = 'ignored')
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
                    'model' => $model ?? 'gpt-4o-mini',
                    'messages' => [
                        ['role' => 'system', 'content' => $systemMessage],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'functions' => [[
                        'name' => 'structured_response',
                        'description' => 'Structured response as defined by schema, stored in a "result" property',
                        'parameters' => [
                            'type' => 'object',
                            'properties' => [
                                'result' => $schema->getSerializableData()
                            ],
                            'required' => ['result']
                        ],
                    ]],
                    'function_call' => ['name' => 'structured_response'],
                ],
            ]);

            $data = $response->toArray();
            $functionCall = (array) json_decode($data['choices'][0]['message']['function_call']['arguments'] ?? null, true);
            return $functionCall ? json_encode($functionCall['result'] ?? null, JSON_PRETTY_PRINT) : 'No structured response';
        } catch (TransportExceptionInterface|ClientException $e) {
            throw new \RuntimeException(
                'Request failed: ' . $e->getMessage() . ' "' . ($response ?? null)?->getContent(false) . '"',
                0,
                $e
            );
        }
    }
}
