<?php
namespace Apie\TypescriptCodeBuilder;

use Apie\Core\Attributes\ConcreteClasses;
use Apie\TypescriptCodeBuilder\Dto\Typehints\ArrayTypeDefinition;
use Apie\TypescriptCodeBuilder\Dto\Typehints\CallbackTypeDefinition;
use Apie\TypescriptCodeBuilder\Dto\Typehints\IdentifierTypeDefinition;
use Apie\TypescriptCodeBuilder\Dto\Typehints\IntersectionTypeDefinition;
use Apie\TypescriptCodeBuilder\Dto\Typehints\LiteralTypeDefinition;
use Apie\TypescriptCodeBuilder\Dto\Typehints\ObjectTypeDefinition;
use Apie\TypescriptCodeBuilder\Dto\Typehints\UndefinedTypeDefinition;
use Apie\TypescriptCodeBuilder\Dto\Typehints\UnionTypeDefinition;
use Apie\TypescriptCodeBuilder\Enums\TypescriptType;

#[ConcreteClasses(
    ArrayTypeDefinition::class,
    CallbackTypeDefinition::class,
    IntersectionTypeDefinition::class,
    TypescriptType::class,
    IdentifierTypeDefinition::class,
    LiteralTypeDefinition::class,
    ObjectTypeDefinition::class,
    UndefinedTypeDefinition::class,
    UnionTypeDefinition::class,
)]
interface TypescriptTypeDeclarationInterface extends TypescriptFileExpressionInterface
{
}
