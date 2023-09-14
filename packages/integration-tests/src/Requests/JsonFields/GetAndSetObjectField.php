<?php

namespace Apie\IntegrationTests\Requests\JsonFields;

use PHPUnit\Framework\TestCase;

class GetAndSetObjectField implements JsonSetFieldInterface, JsonGetFieldInterface
{
    /**
     * @var array<int, JsonGetFieldInterface|JsonSetFieldInterface> $fields
     */
    private array $fields;

    public function __construct(
        private readonly string $name,
        JsonGetFieldInterface|JsonSetFieldInterface... $fields
    ) {
        $this->fields = $fields;
    }

    public function getInputValue(): mixed
    {
        $data = [];
        foreach ($this->fields as $field) {
            if ($field instanceof JsonGetFieldInterface) {
                $data[$field->getName()] = $field->getInputValue();
            }
        }
        return $data;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function assertResponseValue(mixed $responseValue): void
    {
        TestCase::assertIsArray($responseValue);
        foreach ($this->fields as $field) {
            if ($field instanceof JsonSetFieldInterface) {
                $field->assertResponseValue($responseValue[$field->getName()]);
            }
        }
    }
}
