<?php
namespace Apie\TypescriptCodeBuilder\Dto\Typehints;

use Apie\TypescriptCodeBuilder\Dto\FunctionArgument;
use Apie\TypescriptCodeBuilder\Lists\ArgumentList;
use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptTypeDeclarationInterface;

/**
 * Typescript definition for an object type.
 *
 * @param array<int, FunctionArgument> $properties
 */
class ObjectTypeDefinition implements TypescriptTypeDeclarationInterface
{
    public function __construct(
        public ArgumentList $properties,
    ) {
    }

    public function toTypescript(): string
    {
        $properties = [];
        foreach ($this->properties as $property) {
            $properties[] = $property->toTypescript() . ';';
        }
        return '{ ' . implode(' ', $properties) . ' }';
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
        foreach ($this->properties as $property) {
            /** @var FunctionArgument $property */
            foreach ($property->needsDefinitions() as $definition) {
                $definitions = $definitions->append($definition);
            }
        }
        return $definitions;
    }
}
