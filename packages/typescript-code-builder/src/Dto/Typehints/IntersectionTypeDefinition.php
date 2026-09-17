<?php
namespace Apie\TypescriptCodeBuilder\Dto\Typehints;

use Apie\Core\Attributes\FakeMethod;
use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\Enums\TypescriptType;
use Apie\TypescriptCodeBuilder\Lists\TypescriptDeclarationList;
use Apie\TypescriptCodeBuilder\TypescriptTypeDeclarationInterface;

/**
 * Typescript definition for an intersection type.
 *
 * @param array<int, TypescriptTypeDeclarationInterface> $types
 */
#[FakeMethod('createRandom')]
class IntersectionTypeDefinition implements TypescriptTypeDeclarationInterface
{
    public function __construct(
        public TypescriptDeclarationList $types,
    ) {
    }

    public static function createRandom(): self
    {
        return new self(
            new TypescriptDeclarationList([TypescriptType::Boolean, TypescriptType::String])
        );
    }

    public function toTypescript(): string
    {
        return implode(' & ', array_map(
            static fn (TypescriptTypeDeclarationInterface $type): string => '(' . $type->toTypescript() . ')',
            $this->types->toArray(),
        ));
    }

    public function toJavascript(): string
    {
        return '';
    }

    public function providesDefinitions(): JavascriptIdentifierList
    {
        return new JavascriptIdentifierList();
    }

    public function needsDefinitions(): JavascriptIdentifierList
    {
        $definitions = new JavascriptIdentifierList();
        foreach ($this->types as $type) {
            foreach ($type->needsDefinitions() as $definition) {
                $definitions = $definitions->append($definition);
            }
        }
        return $definitions;
    }
}