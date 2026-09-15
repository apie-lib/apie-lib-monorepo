<?php
namespace Apie\TypescriptCodeBuilder;

use Apie\Core\Attributes\ConcreteClasses;
use Apie\Core\Dto\DtoInterface;
use Apie\Core\Lists\IdentifierList;
use Apie\TypescriptCodeBuilder\Dto\IIFE;
use Apie\TypescriptCodeBuilder\Dto\NamedFunction;
use Apie\TypescriptCodeBuilder\Dto\RawJavascript;
use Apie\TypescriptCodeBuilder\Dto\TypescriptDeclaration;

#[ConcreteClasses(IIFE::class, TypescriptDeclaration::class, RawJavascript::class, NamedFunction::class)]
interface TypescriptFileExpressionInterface extends DtoInterface
{
    public function toTypescript(): string;
    public function toJavascript(): string;
    public function providesDefinitions(): IdentifierList;
    public function needsDefinitions(): IdentifierList;
}
