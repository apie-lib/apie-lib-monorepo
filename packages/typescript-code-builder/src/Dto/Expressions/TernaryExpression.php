<?php
namespace Apie\TypescriptCodeBuilder\Dto\Expressions;

use Apie\Core\Attributes\FakeMethod;
use Apie\TypescriptCodeBuilder\Enums\BinaryOperator;
use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;
use Faker\Generator;

#[FakeMethod('createRandom')]
class TernaryExpression implements TypescriptFileExpressionInterface
{
    public function __construct(
        public TypescriptFileExpressionInterface $condition,
        public TypescriptFileExpressionInterface $whenTrue,
        public TypescriptFileExpressionInterface $whenFalse,
    ) {
    }

    public function toTypescript(): string
    {
        return $this->condition->toTypescript() . ' ? ' . $this->whenTrue->toTypescript() . ' : ' . $this->whenFalse->toTypescript();
    }

    public function toJavascript(): string
    {
        return $this->condition->toJavascript() . ' ? ' . $this->whenTrue->toJavascript() . ' : ' . $this->whenFalse->toJavascript();
    }

    public function providesDefinitions(bool $applyBlockScope): JavascriptIdentifierList
    {
        return new JavascriptIdentifierList();
    }

    public function needsDefinitions(): JavascriptIdentifierList
    {
        $definitions = new JavascriptIdentifierList();
        foreach ([$this->condition, $this->whenTrue, $this->whenFalse] as $expression) {
            foreach ($expression->needsDefinitions() as $definition) {
                $definitions = $definitions->append($definition);
            }
        }
        return $definitions;
    }

    public static function createRandom(Generator $faker): self
    {
        $condition = new BinaryOperationExpression(
            $faker->fakeClass(IdentifierExpression::class),
            $faker->randomElement([BinaryOperator::Equal, BinaryOperator::NotEqual]),
            $faker->fakeClass(IdentifierExpression::class)
        );
        return new self(
            $condition,
            $faker->fakeclass(IdentifierExpression::class),
            $faker->fakeClass($faker->randomElement([IdentifierExpression::class, StringLiteralExpression::class, NullExpression::class]))
        );
    }
}
