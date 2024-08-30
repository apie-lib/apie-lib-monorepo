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
        $testedFields = [];
        foreach ($this->fields as $field) {
            if ($field instanceof JsonSetFieldInterface) {
                $fieldName = $field->getName();
                $testedFields[$fieldName] = $fieldName;
                if (!array_key_exists($fieldName, $responseValue)) {
                    TestCase::fail(
                        'field ' . $fieldName . ' is defined, but not found in the response, found: ' . implode(', ', array_keys($responseValue))
                    );
                }
                $field->assertResponseValue($responseValue[$fieldName]);
            }
        }
        foreach (array_keys($responseValue) as $foundFieldname) {
            if (!isset($testedFields[$foundFieldname])) {
                TestCase::fail('Field ' . $foundFieldname . ' is found, but no assertions were added!');
            }
        }
    }
}
