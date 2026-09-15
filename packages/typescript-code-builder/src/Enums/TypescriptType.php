<?php
namespace Apie\TypescriptCodeBuilder\Enums;

use Apie\Core\Lists\IdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptTypeDeclarationInterface;

enum TypescriptType: string implements TypescriptTypeDeclarationInterface
{
    case String = 'string';
    case Number = 'number';
    case Boolean = 'boolean';
    case Any = 'any';
    case Unknown = 'unknown';

    public function toTypescript(): string
    {
        return $this->value;
    }

    public function toJavascript(): string
    {
        return '';
    }

    public function needsDefinitions(): IdentifierList
    {
        return new IdentifierList();
    }

    public function providesDefinitions(): IdentifierList
    {
        return new IdentifierList();
    }
}
