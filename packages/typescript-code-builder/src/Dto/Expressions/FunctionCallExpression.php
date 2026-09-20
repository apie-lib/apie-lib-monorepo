<?php
namespace Apie\TypescriptCodeBuilder\Dto\Expressions;

use Apie\Core\Attributes\FakeMethod;
use Apie\TypescriptCodeBuilder\Lists\ExpressionList;
use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;
use Faker\Generator;

#[FakeMethod('createRandom')]
class FunctionCallExpression implements TypescriptFileExpressionInterface
{
    public function __construct(
        public TypescriptFileExpressionInterface $function,
        public ExpressionList $arguments = new ExpressionList()
    ) {
    }

    public function toTypescript(): string
    {
        return $this->function->toTypescript() . '(' . $this->arguments->toTypescript() . ')';
    }
    public function toJavascript(): string
    {
        return $this->function->toJavascript() . '(' . $this->arguments->toJavascript() . ')';
    }
    public function providesDefinitions(): JavascriptIdentifierList
    {
        return new JavascriptIdentifierList();
    }
    public function needsDefinitions(): JavascriptIdentifierList
    {
        $definitions = $this->function->needsDefinitions();
        foreach ($this->arguments->needsDefinitions() as $definition) {
            $definitions = $definitions->append($definition);
        }
        return $definitions;
    }

    public static function createRandom(Generator $faker): self
    {
        return new self(
            $faker->fakeClass(IdentifierExpression::class),
            $faker->fakeClass(ExpressionList::class)
        );
    }
}
