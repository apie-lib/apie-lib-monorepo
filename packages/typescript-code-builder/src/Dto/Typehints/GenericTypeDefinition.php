<?php
namespace Apie\TypescriptCodeBuilder\Dto\Typehints;

use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\Lists\TypescriptDeclarationList;
use Apie\TypescriptCodeBuilder\TypescriptTypeDeclarationInterface;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifier;

class GenericTypeDefinition implements TypescriptTypeDeclarationInterface
{
    public function __construct(
        public JavascriptIdentifier $name,
        public TypescriptDeclarationList $typeArguments,
    ) {
    }

    public function toTypescript(): string
    {
        $arguments = array_map(
            static fn (TypescriptTypeDeclarationInterface $type): string => $type->toTypescript(),
            $this->typeArguments->toArray(),
        );
        return $this->name->toNative() . '<' . implode(', ', $arguments) . '>';
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
        $definitions = new JavascriptIdentifierList([$this->name]);
        foreach ($this->typeArguments as $type) {
            foreach ($type->needsDefinitions() as $definition) {
                $definitions = $definitions->append($definition);
            }
        }
        return $definitions;
    }
}
