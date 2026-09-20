<?php
namespace Apie\TypescriptCodeBuilder\Dto\Expressions;

use Apie\Core\Attributes\FakeMethod;
use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;
use Faker\Generator;

#[FakeMethod('createRandom')]
class ArrayAccessExpression implements TypescriptFileExpressionInterface
{
    public function __construct(
        public TypescriptFileExpressionInterface $array,
        public TypescriptFileExpressionInterface $index
    ) {
    }

    public function toTypescript(): string
    {
        return $this->array->toTypescript() . '[' . $this->index->toTypescript() . ']';
    }
    public function toJavascript(): string
    {
        return $this->array->toJavascript() . '[' . $this->index->toJavascript() . ']';
    }
    public function providesDefinitions(): JavascriptIdentifierList
    {
        return new JavascriptIdentifierList();
    }
    public function needsDefinitions(): JavascriptIdentifierList
    {
        $definitions = $this->array->needsDefinitions();
        foreach ($this->index->needsDefinitions() as $definition) {
            $definitions = $definitions->append($definition);
        }
        return $definitions;
    }

    public static function createRandom(Generator $faker): self
    {
        return new self(
            $faker->fakeClass(IdentifierExpression::class),
            new NumberLiteralExpression($faker->numberBetween())
        );
    }
}
