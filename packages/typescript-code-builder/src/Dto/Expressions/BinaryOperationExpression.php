<?php
namespace Apie\TypescriptCodeBuilder\Dto\Expressions;

use Apie\Core\Attributes\FakeMethod;
use Apie\TypescriptCodeBuilder\Enums\BinaryOperator;
use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;
use Faker\Generator;

#[FakeMethod('createRandom')]
class BinaryOperationExpression implements TypescriptFileExpressionInterface
{
    public function __construct(
        public TypescriptFileExpressionInterface $left,
        public BinaryOperator $operator,
        public TypescriptFileExpressionInterface $right,
    ) {
    }

    public function toTypescript(): string
    {
        return $this->left->toTypescript() . ' ' . $this->operator->value . ' ' . $this->right->toTypescript();
    }
    public function toJavascript(): string
    {
        return $this->left->toJavascript() . ' ' . $this->operator->value . ' ' . $this->right->toJavascript();
    }
    public function providesDefinitions(bool $applyBlockScope): JavascriptIdentifierList
    {
        return new JavascriptIdentifierList();
    }
    public function needsDefinitions(): JavascriptIdentifierList
    {
        return $this->merge($this->left, $this->right);
    }

    private function merge(TypescriptFileExpressionInterface ...$expressions): JavascriptIdentifierList
    {
        $definitions = new JavascriptIdentifierList();
        foreach ($expressions as $expression) {
            foreach ($expression->needsDefinitions() as $definition) {
                $definitions = $definitions->append($definition);
            }
        }
        return $definitions;
    }

    public static function createRandom(Generator $faker): self
    {
        $operator = $faker->fakeClass(BinaryOperator::class);
        $options = [];
        if ($operator->allowsNumbers()) {
            $options[] = function () use ($faker) {
                return new NumberLiteralExpression($faker->randomNumber());
            };
        }
        if ($operator->allowsBoolean()) {
            $options[] = function () use ($faker) {
                return new BooleanLiteralExpression($faker->boolean());
            };
        }
        if ($operator->allowsNull()) {
            $options[] = function () use ($faker) {
                return new NullExpression();
            };
        }
        if ($operator->allowsString()) {
            $options[] = function () use ($faker) {
                return new StringLiteralExpression($faker->word());
            };
        }
        $pickedOption = $faker->randomElement($options);
        return new self(
            $faker->boolean() ? $pickedOption() : $faker->fakeClass(IdentifierExpression::class),
            $operator,
            $faker->boolean() ? $pickedOption() : $faker->fakeClass(IdentifierExpression::class),
        );
    }
}
