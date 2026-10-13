<?php
namespace Apie\IanaValueObjects\HttpHeader;

use Apie\Core\ValueObjects\NonEmptyString;

trait IsHttpHeader
{
    abstract protected function getFieldValue(string $fieldName): mixed;

    protected static function getData(): array
    {
        return require __DIR__ . '/../../fixtures/http-fields.php';
    }

    protected function convert(string $input): string
    {
        $inputLower = strtolower($input);
        $data = static::getData();
        if (!isset($data[$inputLower])) {
            return $input;
        }

        return $data[$inputLower]['Field Name'] ?? $input;
    }

    public function getFieldName(): string
    {
        return $this->getFieldValue('Field Name');
    }

    public function getStatus(): ?HttpHeaderStatus
    {
        // strtolower as some iana data starts with an uppercase....
        $status = strtolower($this->getFieldValue('Status'));
        return $status ? HttpHeaderStatus::from($status) : null;
    }

    public function getStructuredType(): ?StructuredType
    {
        $structuredType = $this->getFieldValue('Structured_Type');
        return $structuredType ? StructuredType::from($structuredType) : null;
    }

    public function getReference(): string
    {
        return $this->getFieldValue('Reference');
    }

    public function getComments(): ?NonEmptyString
    {
        $comments = $this->getFieldValue('Comments');
        return $comments ? NonEmptyString::fromNative($comments) : null;
    }

    public function isActive(): bool
    {
        return (bool) $this->getFieldValue('Active');
    }
}
