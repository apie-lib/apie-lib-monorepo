<?php
namespace Apie\AiInstructor;

use cebe\openapi\spec\Schema;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class AiClient
{
    abstract public function ask(string $systemMessage, string $prompt, Schema $schema, ?string $model = null): string;
    
    public static function create(
        ?HttpClientInterface $client = null,
        string $baseUrl = 'http://localhost:11434',
        string $apiKey = 'ignored'
    ): AiClient {
        if (str_starts_with($baseUrl, 'https://api.openai.com/v1')) {
            return new OpenAiClient(
                $client,
                $apiKey
            );
        }

        return new OllamaClient($client, $baseUrl);
    }
}
