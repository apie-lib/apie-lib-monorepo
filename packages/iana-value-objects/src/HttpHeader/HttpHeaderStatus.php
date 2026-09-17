<?php
namespace Apie\IanaValueObjects\HttpHeader;

enum HttpHeaderStatus: string
{
    case Permanent = 'permanent';
    case Deprecated = 'deprecated';
    case Obsoleted = 'obsoleted';
    case Provisional = 'provisional';
}
