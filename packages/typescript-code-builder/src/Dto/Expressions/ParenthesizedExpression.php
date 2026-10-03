<?php
namespace Apie\TypescriptCodeBuilder\Dto\Expressions;

use Apie\Core\Attributes\FakeMethod;
use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;
use Faker\Generator;

#[FakeMethod('createRandom')]
class ParenthesizedExpression implements TypescriptFileExpressionInterface
{
    public function __construct(public TypescriptFileExpressionInterface $expression)
    {
    }

    public function toTypescript(): string
    {
        return '(' . $this->expression->toTypescript() . ')';
    }
    public function toJavascript(): string
    {
        return '(' . $this->expression->toJavascript() . ')';
    }
    public function providesDefinitions(bool $applyBlockScope): JavascriptIdentifierList
    {
        return new JavascriptIdentifierList();
    }
    public function needsDefinitions(): JavascriptIdentifierList
    {
        return $this->expression->needsDefinitions();
    }

    public static function createRandom(Generator $faker): self
    {
        return new self($faker->fakeClass(BinaryOperationExpression::class));
    }
}
