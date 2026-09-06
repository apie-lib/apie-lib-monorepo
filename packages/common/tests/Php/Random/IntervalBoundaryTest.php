<?php
namespace Apie\Tests\Php\Random;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Random\IntervalBoundary;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresPhpExtension('random')]
class IntervalBoundaryTest extends ObjectTestCase
{
    public static function className(): string
    {
        return IntervalBoundary::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'string',
            'enum' => [
                IntervalBoundary::ClosedOpen->name,
                IntervalBoundary::ClosedClosed->name,
                IntervalBoundary::OpenClosed->name,
                IntervalBoundary::OpenOpen->name,
            ]
        ];
    }
}