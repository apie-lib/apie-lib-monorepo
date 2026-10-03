<?php
namespace Apie\TypescriptCodeBuilder\Dto\Typehints;

use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\Lists\TypescriptDeclarationList;
use Apie\TypescriptCodeBuilder\TypescriptTypeDeclarationInterface;

/**
 * Typescript definition for a union type.
 *
 * @param array<int, TypescriptTypeDeclarationInterface> $types
 */
class UnionTypeDefinition implements TypescriptTypeDeclarationInterface
{
    private bool $simple = true;
    public function __construct(
        public TypescriptDeclarationList $types,
    ) {
        foreach ($types as $type) {
            if ($type instanceof IdentifierTypeDefinition || $type instanceof LiteralTypeDefinition) {
            } else {
                $this->simple = false;
                break;
            }
        }
    }

    public function toTypescript(): string
    {
        if ($this->simple) {
            return implode(' | ', array_map(
                static fn (TypescriptTypeDeclarationInterface $type): string => $type->toTypescript(),
                $this->types->toArray(),
            ));
        }
        return implode(' | ', array_map(
            static fn (TypescriptTypeDeclarationInterface $type): string => '(' . $type->toTypescript() . ')',
            $this->types->toArray(),
        ));
    }

    public function toJavascript(): string
    {
        return '';
    }

    public function providesDefinitions(bool $applyBlockScope): JavascriptIdentifierList
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
