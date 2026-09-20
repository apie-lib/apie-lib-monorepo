<?php
namespace Apie\TypescriptCodeBuilder\Dto\Expressions;

use Apie\Core\Attributes\FakeMethod;
use Apie\TypescriptCodeBuilder\Enums\TypescriptType;
use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;
use Apie\TypescriptCodeBuilder\TypescriptTypeDeclarationInterface;
use Faker\Generator;

#[FakeMethod('createRandom')]
class AsExpression implements TypescriptFileExpressionInterface
{
    public function __construct(public TypescriptFileExpressionInterface $expression, public TypescriptTypeDeclarationInterface $type)
    {
    }

    public function toTypescript(): string
    {
        return $this->expression->toTypescript() . ' as ' . $this->type->toTypescript();
    }
    public function toJavascript(): string
    {
        return $this->expression->toJavascript();
    }
    public function providesDefinitions(): JavascriptIdentifierList
    {
        return new JavascriptIdentifierList();
    }
    public function needsDefinitions(): JavascriptIdentifierList
    {
        $definitions = $this->expression->needsDefinitions();
        foreach ($this->type->needsDefinitions() as $definition) {
            $definitions = $definitions->append($definition);
        }
        return $definitions;
    }

    public static function createRandom(Generator $faker): self
    {
        return new AsExpression(
            $faker->fakeClass(ObjectExpression::class),
            TypescriptType::Any
        );
    }
}
