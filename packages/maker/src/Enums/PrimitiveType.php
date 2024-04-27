<?php
namespace Apie\Maker\Enums;

use Apie\Core\Attributes\RequiresPhpVersion;
use Apie\Core\Attributes\StaticCheck;

enum PrimitiveType: string
{
    case String = 'string';
    case Integer = 'int';
    case FloatingPoint = 'float';
    case Array = 'array';
    #[StaticCheck(new RequiresPhpVersion('>=8.2'))]
    case Null = 'null';
}
