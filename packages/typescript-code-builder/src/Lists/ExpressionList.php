<?php
namespace Apie\TypescriptCodeBuilder\Lists;

use Apie\Core\Attributes\FakeMethod;
use Apie\Core\Lists\ItemList;
use Apie\TypescriptCodeBuilder\Dto\Expressions\IdentifierExpression;
use Apie\TypescriptCodeBuilder\Dto\Expressions\StringLiteralExpression;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;
use Faker\Generator;

#[FakeMethod('createRandom')]
class ExpressionList extends ItemList
{
    public function offsetGet(mixed $offset): TypescriptFileExpressionInterface
    {
        return parent::offsetGet($offset);
    }

    public function toTypescript(): string
    {
        return implode(', ', array_map(
            static fn (TypescriptFileExpressionInterface $expression): string => $expression->toTypescript(),
            $this->toArray(),
        ));
    }

    public function toJavascript(): string
    {
        return implode(', ', array_map(
            static fn (TypescriptFileExpressionInterface $expression): string => $expression->toJavascript(),
            $this->toArray(),
        ));
    }

    public function needsDefinitions(): JavascriptIdentifierList
    {
        $definitions = new JavascriptIdentifierList();
        foreach ($this as $expression) {
            foreach ($expression->needsDefinitions() as $definition) {
                $definitions = $definitions->append($definition);
            }
        }
        return $definitions;
    }

    public static function createRandom(Generator $faker): self
    {
        $list = [];
        $count = $faker->numberBetween(0, 3);
        for ($i = 0; $i < $count; $i++) {
            $list[] = $faker->fakeClass($faker->boolean() ? IdentifierExpression::class : StringLiteralExpression::class);
        }
        return new ExpressionList($list);
    }
}
