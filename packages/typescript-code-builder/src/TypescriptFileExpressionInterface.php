<?php
namespace Apie\TypescriptCodeBuilder;

use Apie\Core\Attributes\ConcreteClasses;
use Apie\Core\Dto\DtoInterface;
use Apie\TypescriptCodeBuilder\Dto\IIFE;
use Apie\TypescriptCodeBuilder\Dto\ImportStatement;
use Apie\TypescriptCodeBuilder\Dto\NamedFunction;
use Apie\TypescriptCodeBuilder\Dto\RawJavascript;
use Apie\TypescriptCodeBuilder\Dto\TypescriptDeclaration;
use Apie\TypescriptCodeBuilder\Dto\VariableAssignment;
use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;

#[ConcreteClasses(IIFE::class, ImportStatement::class, TypescriptDeclaration::class, RawJavascript::class, NamedFunction::class, VariableAssignment::class)]
interface TypescriptFileExpressionInterface extends DtoInterface
{
    public function toTypescript(): string;
    public function toJavascript(): string;
    public function providesDefinitions(): JavascriptIdentifierList;
    public function needsDefinitions(): JavascriptIdentifierList;
}
