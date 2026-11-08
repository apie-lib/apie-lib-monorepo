<?php
namespace Apie\TypescriptCodeBuilder\Dto\Typehints;

use Apie\TypescriptCodeBuilder\Dto\FunctionArgument;
use Apie\TypescriptCodeBuilder\Lists\ArgumentList;
use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptTypeDeclarationInterface;
use Apie\TypescriptCodeBuilder\Utils\ControlFlowUtils;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifier;

/**
 * Typescript definition for an interface.
 */
class InterfaceDefinition implements TypescriptTypeDeclarationInterface
{
    public function __construct(
        public JavascriptIdentifier $name,
        public ArgumentList $properties,
        public ?JavascriptIdentifierList $extends = null
    ) {
    }

    public function toTypescript(): string
    {
        $properties = [];
        foreach ($this->properties as $property) {
            $properties[] = $property->toTypescript() . ';';
        }
        $extends = '';
        if ($this->extends && $this->extends->count()) {
            $extends = ' extends ' . implode(', ', $this->extends->toStringArray());
        }
        return 'interface ' . $this->name->toNative() . $extends . ' {' . PHP_EOL . ControlFlowUtils::indent(implode(PHP_EOL, $properties)) . PHP_EOL . '}';
    }

    public function toJavascript(): string
    {
        return '';
    }

    public function providesDefinitions(bool $applyBlockScope): JavascriptIdentifierList
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
        if ($this->extends) {
            foreach ($this->extends as $implement) {
                $definitions = $definitions->append($implement);
            }
        }
        return $definitions;
    }
}
