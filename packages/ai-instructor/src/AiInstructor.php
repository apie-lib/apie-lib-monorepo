<?php
namespace Apie\AiInstructor;

use Apie\Core\Context\ApieContext;
use Apie\Core\ValueObjects\NonEmptyString;
use Apie\SchemaGenerator\ComponentsBuilderFactory;
use Apie\SchemaGenerator\SchemaGenerator;
use Apie\Serializer\Serializer;
use ReflectionNamedType;
use ReflectionUnionType;
use Symfony\Component\HttpClient\HttpClient;

final class AiInstructor
{
    public function __construct(
        private readonly SchemaGenerator $schemaGenerator,
        private readonly Serializer $serializer,
        private readonly AiClient $aiClient
    ) {
    }

    public function instruct(
        ReflectionNamedType|ReflectionUnionType $type,
        NonEmptyString $model,
        string $systemMessage,
        string $prompt
    ) {
        $schema = $this->schemaGenerator->createSchema((string) $type);
        $response = $this->aiClient->ask(
            $systemMessage,
            $prompt,
            $schema,
            $model
        );
        try {
            return $this->serializer->denormalizeNewObject(
                json_decode($response, true),
                (string) $type,
                new ApieContext()
            );
        } catch (\Exception $exception) {
            throw new \LogicException(
                "I could not map the AI response '" . $response . "' to '" . ((string) $type) . "', error: '" . $exception->getMessage() . '"',
                0,
                $exception
            );
        }
    }

    public static function createForCustomConfig(string $apiKey, string $baseUrl): self
    {
        return new self(
            new SchemaGenerator(ComponentsBuilderFactory::createComponentsBuilderFactory()),
            Serializer::create(),
            new AiClient(
                HttpClient::create([
                    'max_redirects' => 7,
                ]),
                $apiKey,
                $baseUrl
            )
        );
    }

    public static function createForOllama(): self
    {
        return self::createForCustomConfig(
            'IGNORED',
            'http://localhost:11434/',
        );
    }
}
