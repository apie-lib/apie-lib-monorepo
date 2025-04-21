<?php
namespace Apie\Tests\AiInstructor;

use Apie\AiInstructor\AiClient;
use Apie\AiInstructor\AiInstructor;
use Apie\AiInstructor\ExampleClass;
use Apie\Core\ValueObjects\NonEmptyString;
use Apie\SchemaGenerator\ComponentsBuilderFactory;
use Apie\SchemaGenerator\SchemaGenerator;
use Apie\Serializer\Serializer;
use Apie\TypeConverter\ReflectionTypeFactory;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
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
            new AiClient(
                new MockHttpClient($responseFactory),
                'api key',
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
}
