<?php
namespace Apie\TypescriptCodeBuilder\Enums;

enum UnaryOperator: string
{
    case Not = '!';
    case BitwiseNot = '~';
    case Plus = '+';
    case Minus = '-';
    case Typeof = 'typeof';
    case Void = 'void';
    case Delete = 'delete';
}
