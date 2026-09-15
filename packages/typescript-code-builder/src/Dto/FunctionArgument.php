<?php
namespace Apie\TypescriptCodeBuilder\Dto;

use Apie\Core\Identifiers\Identifier;
use Apie\Core\Lists\IdentifierList;
use Apie\TypescriptCodeBuilder\Enums\TypescriptType;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;
use Apie\TypescriptCodeBuilder\TypescriptTypeDeclarationInterface;

class FunctionArgument implements TypescriptFileExpressionInterface
{
    public function __construct(
        public Identifier $name,
        public ?TypescriptTypeDeclarationInterface $typehint = null,
        public bool $optional = false,
    ) {
    }

    public function toTypescript(): string
    {
        if ($this->typehint === null) {
            return $this->name->toNative() . ($this->optional ? '?: unknown' : '');
        }
        return $this->name->toNative() . ($this->optional ? '?: ' : ': ') . $this->typehint->toTypescript();
    }
    public function toJavascript(): string
    {
        return $this->name->toNative();
    }
    public function providesDefinitions(): IdentifierList
    {
        return new IdentifierList();
    }
    public function needsDefinitions(): IdentifierList
    {
        return $this->typehint ? $this->typehint->needsDefinitions() : new IdentifierList();
    }
}