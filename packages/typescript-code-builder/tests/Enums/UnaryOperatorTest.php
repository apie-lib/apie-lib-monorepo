<?php
namespace Apie\Tests\TypescriptCodeBuilder\Enums;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Enums\UnaryOperator;

class UnaryOperatorTest extends ObjectTestCase
{
    public static function className(): string
    {
        return UnaryOperator::class;
    }
    public static function getOpenApiSchemaForCreation(): array
    {
        return ['type' => 'string', 'enum' => array_column(UnaryOperator::cases(), 'value')];
    }
}
