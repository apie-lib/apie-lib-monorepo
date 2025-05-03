<?php
namespace Apie\Tests\AiInstructor;

use Apie\AiInstructor\AiClient;
use Apie\AiInstructor\AiInstructor;
use Apie\AiInstructor\OllamaClient;
use Apie\Core\ValueObjects\NonEmptyString;
use Apie\SchemaGenerator\ComponentsBuilderFactory;
use Apie\SchemaGenerator\SchemaGenerator;
use Apie\Serializer\Serializer;
use Apie\TypeConverter\ReflectionTypeFactory;
use Generator;
use LogicException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class AiInstructorTest extends TestCase
{
    #[Test]
    #[DataProvider('apiCallProvider')]
    public function it_can_hydrate_from_an_api_call(string $expectedApiCallUrl, array $content, string $apiUrl)
    {
        $responseFactory = function ($method, $url, $options) use ($content, $expectedApiCallUrl) {
            TestCase::assertEquals('POST', $method);
            TestCase::assertEquals($expectedApiCallUrl, $url);

            return new MockResponse(json_encode($content));
        };
        $testItem = new AiInstructor(
            new SchemaGenerator(ComponentsBuilderFactory::createComponentsBuilderFactory()),
            Serializer::create(),
            AiClient::create(
                new MockHttpClient($responseFactory),
                $apiUrl,
                'secret'
            )
        );
        $testItem->instruct(
            ReflectionTypeFactory::createReflectionType('string'),
            NonEmptyString::fromNative('test-model'),
            'System message',
            'User prompt'
        );
    }

    public static function apiCallProvider(): Generator
    {
        $ollamaResponse = [
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
        ];
        yield 'ollama client' => [
            'http://obama:12345/api/chat',
            $ollamaResponse,
            'http://obama:12345'
        ];
        $openAiResponse = [
            "id" => "chatcmpl-BSkki1Duo07WS8p2VIdIUJ9TdUwaL",
            "object" => "chat.completion",
            "created" => 1746192600,
            "model" => "test-model-2024-07-18",
            "choices" => [
              [
                "index" => 0,
                "message" => [
                  "role" => "assistant",
                  "content" => null,
                  "function_call" => [
                    "name" => "structured_response",
                    "arguments" => '{"result":"CZ"}'
                  ],
                  "refusal" => null,
                  "annotations" => []
                ],
                "logprobs" => null,
                "finish_reason" => "stop"
              ]
            ],
            "usage" => [
              "prompt_tokens" => 1129,
              "completion_tokens" => 6,
              "total_tokens" => 1135,
              "prompt_tokens_details" => [
                "cached_tokens" => 0,
                "audio_tokens" => 0,
              ],
              "completion_tokens_details" => [
                "reasoning_tokens" => 0,
                "audio_tokens" => 0,
                "accepted_prediction_tokens" => 0,
                "rejected_prediction_tokens" => 0,
              ]
              ],
            "service_tier" => "default",
            "system_fingerprint" => "fp_129a36352a",
        ];
        yield 'open AI client' => [
            'https://api.openai.com/v1/chat/completions',
            $openAiResponse,
            'https://api.openai.com/v1'
        ];
    }

    #[Test]
    public function it_can_throw_error_on_serialization_error_from_an_api_call()
    {
        $responseFactory = function ($method, $url, $options) {
            TestCase::assertEquals('POST', $method);
            TestCase::assertEquals('http://osama:12345/api/api/chat', $url);

            return new MockResponse(
                json_encode(
                    [
                        "model" => "tinyllama",
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
                    ]
                )
            );
        };
        $testItem = new AiInstructor(
            new SchemaGenerator(ComponentsBuilderFactory::createComponentsBuilderFactory()),
            Serializer::create(),
            new OllamaClient(
                new MockHttpClient($responseFactory),
                'http://osama:12345/api',
            )
        );
        $this->expectExceptionMessage("I could not map the AI response '\"CZ\"' to 'int', error: 'Type \"CZ\" is not expected, expected int\"");
        $this->expectException(LogicException::class);
        $testItem->instruct(
            ReflectionTypeFactory::createReflectionType('int'),
            NonEmptyString::fromNative('test-model'),
            'System message',
            'User prompt'
        );
    }

    #[Test]
    public function it_can_throw_error_on_failed_api_call()
    {
        $responseFactory = function ($method, $url, $options) {
            TestCase::assertEquals('POST', $method);
            TestCase::assertEquals('http://ollama:12345/api/api/chat', $url);

            throw new TransportException('Connection refused');
        };
        $testItem = new AiInstructor(
            new SchemaGenerator(ComponentsBuilderFactory::createComponentsBuilderFactory()),
            Serializer::create(),
            new OllamaClient(
                new MockHttpClient($responseFactory),
                'http://ollama:12345/api',
            )
        );
        $this->expectExceptionMessage("Request failed: Connection refused \"\"");
        $this->expectException(RuntimeException::class);
        $testItem->instruct(
            ReflectionTypeFactory::createReflectionType('int'),
            NonEmptyString::fromNative('test-model'),
            'System message',
            'User prompt'
        );
    }
}
