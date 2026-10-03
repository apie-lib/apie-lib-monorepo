<?php
namespace Apie\IanaValueObjects\HttpHeader;

enum StructuredType: string
{
    case Item = 'Item';
    case List = 'List';
    case Dictionary = 'Dictionary';
    case Token = 'Token';
}
