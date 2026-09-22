<?php
namespace Apie\TypescriptCodeBuilder\Enums;

use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptTypeDeclarationInterface;

enum TypescriptType: string implements TypescriptTypeDeclarationInterface
{
    case String = 'string';
    case Number = 'number';
    case Boolean = 'boolean';
    case Any = 'any';
    case Unknown = 'unknown';
    case Void = 'void';
    case BigInt = 'bigint';
    case Symbol = 'symbol';

    public function toTypescript(): string
    {
        return $this->value;
    }

    public function toJavascript(): string
    {
        return '';
    }

    public function needsDefinitions(): JavascriptIdentifierList
    {
        return new JavascriptIdentifierList();
    }

    public function providesDefinitions(bool $applyBlockScope): JavascriptIdentifierList
    {
        return new JavascriptIdentifierList();
    }
}
