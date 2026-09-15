<?php
namespace Apie\TypescriptCodeBuilder;

use Apie\Core\Attributes\ConcreteClasses;
use Apie\TypescriptCodeBuilder\Dto\Typehints\ArrayTypeDefinition;
use Apie\TypescriptCodeBuilder\Dto\Typehints\IdentifierTypeDefinition;
use Apie\TypescriptCodeBuilder\Enums\TypescriptType;

#[ConcreteClasses(ArrayTypeDefinition::class, TypescriptType::class, IdentifierTypeDefinition::class)]
interface TypescriptTypeDeclarationInterface extends TypescriptFileExpressionInterface
{
}
