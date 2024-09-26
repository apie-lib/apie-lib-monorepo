<?php
namespace Apie\Core\Attributes;

use Apie\Core\Dto\CmsInputOption;
use Apie\Core\Lists\StringList;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS|Attribute::TARGET_METHOD|Attribute::TARGET_PROPERTY|Attribute::TARGET_PARAMETER)]
final class CmsValidationCheck
{
    public function __construct(private readonly string $patternMethod)
    {
    }
}