<?php
namespace Apie\Tests\TypescriptCodeBuilder\Enums;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Enums\BinaryOperator;

class BinaryOperatorTest extends ObjectTestCase
{
    public static function className(): string
    {
        return BinaryOperator::class;
    }
    public static function getOpenApiSchemaForCreation(): array
    {
        return ['type' => 'string', 'enum' => array_column(BinaryOperator::cases(), 'value')];
    }
}
