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
            if (class_exists(ValidationException::class)) {
                $this->assertInstanceOf(ValidationException::class, $error);
                $this->assertEquals($expectedErrorMessages, $error->getErrors()->toArray());
            }
            $this->assertTrue(true, 'it works as intended');
            return;
        }
        $this->fail('No validation error was thrown!');
    }
}
