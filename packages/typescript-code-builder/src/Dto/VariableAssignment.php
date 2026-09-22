<?php
namespace Apie\TypescriptCodeBuilder\Dto;

use Apie\Core\Attributes\FakeMethod;
use Apie\TypescriptCodeBuilder\Dto\Expressions\ArrayAccessExpression;
use Apie\TypescriptCodeBuilder\Dto\Expressions\IdentifierExpression;
use Apie\TypescriptCodeBuilder\Dto\Expressions\NumberLiteralExpression;
use Apie\TypescriptCodeBuilder\Dto\Expressions\ObjectExpression;
use Apie\TypescriptCodeBuilder\Dto\Expressions\StringLiteralExpression;
use Apie\TypescriptCodeBuilder\Dto\Expressions\TernaryExpression;
use Apie\TypescriptCodeBuilder\Dto\Expressions\UnaryOperationExpression;
use Apie\TypescriptCodeBuilder\Enums\VariableDeclarationKind;
use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;
use Apie\TypescriptCodeBuilder\TypescriptTypeDeclarationInterface;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifier;
use Faker\Generator;

#[FakeMethod('createRandom')]
class VariableAssignment implements TypescriptFileExpressionInterface
{
    public function __construct(
        public VariableDeclarationKind $kind,
        public JavascriptIdentifier $name,
        public TypescriptFileExpressionInterface $expression,
        public ?TypescriptTypeDeclarationInterface $typehint = null,
    ) {
    }

    public function toTypescript(): string
    {
        $typehint = $this->typehint ? ': ' . $this->typehint->toTypescript() : '';
        return $this->kind->value . ' ' . $this->name->toNative() . $typehint . ' = ' . $this->expression->toTypescript() . ';';
    }

    public function toJavascript(): string
    {
        return $this->kind->value . ' ' . $this->name->toNative() . ' = ' . $this->expression->toJavascript() . ';';
    }

    public function providesDefinitions(bool $applyBlockScope): JavascriptIdentifierList
    {
        if ($applyBlockScope && $this->kind !== VariableDeclarationKind::Var) {
            return new JavascriptIdentifierList();
        }
        return new JavascriptIdentifierList([$this->name]);
    }

    public function needsDefinitions(): JavascriptIdentifierList
    {
        $definitions = $this->typehint?->needsDefinitions() ?? new JavascriptIdentifierList();
        foreach ($this->expression->needsDefinitions() as $definition) {
            $definitions = $definitions->append($definition);
        }
        return $definitions;
    }

    public static function createRandom(Generator $faker): self
    {
        return new VariableAssignment(
            $faker->fakeClass(VariableDeclarationKind::class),
            $faker->fakeClass(JavascriptIdentifier::class),
            $faker->fakeClass(
                $faker->randomElement([
                    ArrayAccessExpression::class,
                    IdentifierExpression::class,
                    NumberLiteralExpression::class,
                    StringLiteralExpression::class,
                    ObjectExpression::class,
                    TernaryExpression::class,
                    UnaryOperationExpression::class,
                ])
            )
        );
    }
}
