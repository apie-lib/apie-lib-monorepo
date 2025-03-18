<?php

namespace Apie\IntegrationTests\Requests\JsonFields;

use PHPUnit\Framework\TestCase;

class GetPrimitiveField implements JsonSetFieldInterface
{
    public function __construct(
        private readonly string $name,
        private readonly mixed $output
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function assertResponseValue(mixed $responseValue): void
    {
        TestCase::assertEquals(
            $this->output,
            $responseValue,
            'Field ' . $this->name . ' should contain this value'
        );
    }
}
