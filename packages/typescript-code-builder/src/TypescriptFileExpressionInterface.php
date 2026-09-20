<?php
namespace Apie\TypescriptCodeBuilder;

use Apie\Core\Attributes\ConcreteClasses;
use Apie\Core\Dto\DtoInterface;
use Apie\TypescriptCodeBuilder\Dto\Expressions\ArrayAccessExpression;
use Apie\TypescriptCodeBuilder\Dto\Expressions\AsExpression;
use Apie\TypescriptCodeBuilder\Dto\Expressions\BinaryOperationExpression;
use Apie\TypescriptCodeBuilder\Dto\Expressions\BooleanLiteralExpression;
use Apie\TypescriptCodeBuilder\Dto\Expressions\FunctionCallExpression;
use Apie\TypescriptCodeBuilder\Dto\Expressions\IdentifierExpression;
use Apie\TypescriptCodeBuilder\Dto\Expressions\NullExpression;
use Apie\TypescriptCodeBuilder\Dto\Expressions\NumberLiteralExpression;
use Apie\TypescriptCodeBuilder\Dto\Expressions\ObjectExpression;
use Apie\TypescriptCodeBuilder\Dto\Expressions\ParenthesizedExpression;
use Apie\TypescriptCodeBuilder\Dto\Expressions\PropertyAccessExpression;
use Apie\TypescriptCodeBuilder\Dto\Expressions\StringLiteralExpression;
use Apie\TypescriptCodeBuilder\Dto\Expressions\TernaryExpression;
use Apie\TypescriptCodeBuilder\Dto\Expressions\TypeCastExpression;
use Apie\TypescriptCodeBuilder\Dto\Expressions\UnaryOperationExpression;
use Apie\TypescriptCodeBuilder\Dto\Expressions\UndefinedExpression;
use Apie\TypescriptCodeBuilder\Dto\IIFE;
use Apie\TypescriptCodeBuilder\Dto\ImportStatement;
use Apie\TypescriptCodeBuilder\Dto\NamedFunction;
use Apie\TypescriptCodeBuilder\Dto\RawJavascript;
use Apie\TypescriptCodeBuilder\Dto\TypescriptDeclaration;
use Apie\TypescriptCodeBuilder\Dto\VariableAssignment;
use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;

#[ConcreteClasses(
    IIFE::class,
    ImportStatement::class,
    TypescriptDeclaration::class,
    RawJavascript::class,
    NamedFunction::class,
    VariableAssignment::class,
    ArrayAccessExpression::class,
    AsExpression::class,
    BinaryOperationExpression::class,
    BooleanLiteralExpression::class,
    FunctionCallExpression::class,
    IdentifierExpression::class,
    NullExpression::class,
    NumberLiteralExpression::class,
    ObjectExpression::class,
    ParenthesizedExpression::class,
    PropertyAccessExpression::class,
    StringLiteralExpression::class,
    TypeCastExpression::class,
    UndefinedExpression::class,
    UnaryOperationExpression::class,
    TernaryExpression::class,
)]
interface TypescriptFileExpressionInterface extends DtoInterface
{
    public function toTypescript(): string;
    public function toJavascript(): string;
    public function providesDefinitions(): JavascriptIdentifierList;
    public function needsDefinitions(): JavascriptIdentifierList;
}
