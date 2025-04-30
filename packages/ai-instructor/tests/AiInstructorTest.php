<?php
namespace Apie\Tests\AiInstructor;

use Apie\AiInstructor\AiInstructor;
use Apie\AiInstructor\OllamaClient;
use Apie\Core\ValueObjects\NonEmptyString;
use Apie\SchemaGenerator\ComponentsBuilderFactory;
use Apie\SchemaGenerator\SchemaGenerator;
use Apie\Serializer\Serializer;
use Apie\TypeConverter\ReflectionTypeFactory;
use LogicException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class AiInstructorTest extends TestCase
{
    #[Test]
    public function it_can_hydrate_from_an_api_call()
    {
        $responseFactory = function ($method, $url, $options) {
            TestCase::assertEquals('POST', $method);
            TestCase::assertEquals('http://obama:12345/api/api/chat', $url);

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
                'http://obama:12345/api',
            )
        );
        $testItem->instruct(
            ReflectionTypeFactory::createReflectionType('string'),
            NonEmptyString::fromNative('test-model'),
            'System message',
            'User prompt'
        );
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
        $this->expectExceptionMessage("I could not map the AI response 'Request failed: Connection refused' to 'int'");
        $this->expectException(LogicException::class);
        $testItem->instruct(
            ReflectionTypeFactory::createReflectionType('int'),
            NonEmptyString::fromNative('test-model'),
            'System message',
            'User prompt'
        );
    }
}
