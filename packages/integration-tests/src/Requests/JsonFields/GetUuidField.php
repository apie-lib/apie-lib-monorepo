<?php

namespace Apie\IntegrationTests\Requests\JsonFields;

use Apie\Core\Identifiers\Uuid;
use PHPUnit\Framework\TestCase;

class GetUuidField implements JsonSetFieldInterface
{
    public function __construct(
        private readonly string $name
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function assertResponseValue(mixed $responseValue): void
    {
        TestCase::assertMatchesRegularExpression(
            Uuid::getRegularExpression(),
            $responseValue,
            'Field ' . $this->name . ' should be a valid uuid'
        );
    }
}
