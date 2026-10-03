<?php
namespace Apie\IntegrationTests\Applications;

use Apie\AiInstructor\AiClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

final class MockFactory
{
    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    public static function createMockAiClient(): AiClient
    {
        $responseFactory = function () {
            return new MockResponse(json_encode([
                "model" => "test-model",
                "created_at" => "2025-04-19T14:03:51.5655428Z",
                "message" => [
                    "role" => "assistant",
                    "content" => '"CZ"',
                ],
                "done_reason" => "stop",
                "done" => true,
                "total_duration" => 2320197200,
                "load_duration" => 18822000,
                "prompt_eval_count" => 69,
                "prompt_eval_duration" => 1945480500,
                "eval_count" => 5,
                "eval_duration" => 341499600,
            ]));
        };
        return AiClient::create(
            new MockHttpClient($responseFactory),
            'http://llm:5432/test',
            'secret'
        );
    }
}
