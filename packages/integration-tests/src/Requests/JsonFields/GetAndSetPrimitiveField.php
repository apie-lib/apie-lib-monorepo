<?php

namespace Apie\IntegrationTests\Requests\JsonFields;

use PHPUnit\Framework\TestCase;

class GetAndSetPrimitiveField implements JsonGetFieldInterface, JsonSetFieldInterface
{
    public function __construct(
        private readonly string $name,
        private readonly mixed $input,
        private mixed $output = null
    ) {
        if (func_num_args() < 3) {
            $this->output = $input;
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getInputValue(): mixed
    {
        return $this->input;
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
