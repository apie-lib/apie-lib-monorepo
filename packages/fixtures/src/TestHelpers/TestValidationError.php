<?php
namespace Apie\Fixtures\TestHelpers;

use Apie\Serializer\Exceptions\ValidationException;
use Throwable;

/** @codeCoverageIgnore */
trait TestValidationError
{
    public function assertValidationError(array $expectedErrorMessages, callable $testCase)
    {
        try {
            $testCase();
        } catch (Throwable $error) {
            $this->assertInstanceOf(ValidationException::class, $error);
            $this->assertEquals($expectedErrorMessages, $error->getErrors()->toArray());
            return;
        }
        $this->fail('No validation error was thrown!');
    }
}
