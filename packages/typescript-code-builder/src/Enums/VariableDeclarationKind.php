<?php
namespace Apie\TypescriptCodeBuilder\Enums;

enum VariableDeclarationKind: string
{
    case Let = 'let';
    case Const = 'const';
    case Var = 'var';
}
