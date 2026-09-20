<?php
namespace Apie\TypescriptCodeBuilder\Dto\Expressions;

use Apie\Core\Attributes\FakeMethod;
use Apie\TypescriptCodeBuilder\Enums\UnaryOperator;
use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;
use Faker\Generator;

#[FakeMethod('createRandom')]
class UnaryOperationExpression implements TypescriptFileExpressionInterface
{
    public function __construct(public UnaryOperator $operator, public TypescriptFileExpressionInterface $expression)
    {
    }

    public function toTypescript(): string
    {
        return $this->operator->value . ' ' . $this->expression->toTypescript();
    }
    public function toJavascript(): string
    {
        return $this->operator->value . ' ' . $this->expression->toJavascript();
    }
    public function providesDefinitions(): JavascriptIdentifierList
    {
        return new JavascriptIdentifierList();
    }
    public function needsDefinitions(): JavascriptIdentifierList
    {
        return $this->expression->needsDefinitions();
    }

    public static function createRandom(Generator $faker): self
    {
        return new self(
            $faker->fakeClass(UnaryOperator::class),
            $faker->fakeClass(ParenthesizedExpression::class)
        );
    }
}
