<?php

namespace Apie\IntegrationTests\Requests\JsonFields;

class SetPrimitiveField implements JsonGetFieldInterface
{
    public function __construct(
        private readonly string $name,
        private readonly mixed $input
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getInputValue(): mixed
    {
        return $this->input;
    }
}
