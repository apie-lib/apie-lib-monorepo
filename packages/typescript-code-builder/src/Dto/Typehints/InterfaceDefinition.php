<?php
namespace Apie\TypescriptCodeBuilder\Dto\Typehints;

use Apie\TypescriptCodeBuilder\Dto\FunctionArgument;
use Apie\TypescriptCodeBuilder\Lists\ArgumentList;
use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptTypeDeclarationInterface;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifier;

/**
 * Typescript definition for an interface.
 */
class InterfaceDefinition implements TypescriptTypeDeclarationInterface
{
    public function __construct(
        public JavascriptIdentifier $name,
        public ArgumentList $properties,
    ) {
    }

    public function toTypescript(): string
    {
        $properties = [];
        foreach ($this->properties as $property) {
            $properties[] = $property->toTypescript() . ';';
        }
        return 'interface ' . $this->name->toNative() . ' { ' . implode(' ', $properties) . ' }';
    }

    public function toJavascript(): string
    {
        return '';
    }

    public function providesDefinitions(): JavascriptIdentifierList
    {
        return new JavascriptIdentifierList([$this->name]);
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
