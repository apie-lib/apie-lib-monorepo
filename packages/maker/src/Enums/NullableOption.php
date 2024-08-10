<?php
namespace Apie\Maker\Enums;

enum NullableOption: string
{
    case NeverNullable = 'never null';
    case InitialNull = 'only initially null';
    case AlwaysNull = 'can always be null';
}
