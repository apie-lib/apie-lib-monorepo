<?php
namespace Apie\IntegrationTests\Requests\JsonFields;

use PHPUnit\Framework\TestCase;

class GetAndSetUploadedFileField extends GetAndSetObjectField
{
    public function __construct(
        private readonly string $name,
        string $fileContents,
        string $originalFilename,
        private readonly string $expectedResponse
    ) {
        parent::__construct(
            $name,
            new GetAndSetPrimitiveField('contents', $fileContents),
            new GetAndSetPrimitiveField('originalFilename', $originalFilename),
        );
    }

    public function assertResponseValue(mixed $responseValue): void
    {
        TestCase::assertEquals(
            $this->expectedResponse,
            $responseValue,
            'Field ' . $this->name . ' should contain this value'
        );
    }
}