<?php
namespace Apie\TypescriptCodeBuilder\Dto\Expressions;

use Apie\Core\Attributes\FakeMethod;
use Apie\TypescriptCodeBuilder\Enums\TypescriptType;
use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;
use Apie\TypescriptCodeBuilder\TypescriptTypeDeclarationInterface;
use Faker\Generator;

#[FakeMethod('createRandom')]
class TypeCastExpression implements TypescriptFileExpressionInterface
{
    public function __construct(public TypescriptTypeDeclarationInterface $type, public TypescriptFileExpressionInterface $expression)
    {
    }

    public function toTypescript(): string
    {
        return '<' . $this->type->toTypescript() . '>' . $this->expression->toTypescript();
    }
    public function toJavascript(): string
    {
        return $this->expression->toJavascript();
    }
    public function providesDefinitions(bool $applyBlockScope): JavascriptIdentifierList
    {
        return new JavascriptIdentifierList();
    }
    public function needsDefinitions(): JavascriptIdentifierList
    {
        $definitions = $this->type->needsDefinitions();
        foreach ($this->expression->needsDefinitions() as $definition) {
            $definitions = $definitions->append($definition);
        }
        return $definitions;
    }

    public static function createRandom(Generator $faker): self
    {
        return new self(
            TypescriptType::Any,
            $faker->fakeClass($faker->randomElement([IdentifierExpression::class, BinaryOperationExpression::class])),
        );
    }
}
