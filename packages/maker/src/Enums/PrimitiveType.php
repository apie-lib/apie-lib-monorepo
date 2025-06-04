<?php
namespace Apie\Maker\Enums;

enum PrimitiveType: string
{
    case String = 'string';
    case Integer = 'int';
    case FloatingPoint = 'float';
    case Array = 'array';
    case Null = 'null';
    case Mixed = 'mixed';
}
