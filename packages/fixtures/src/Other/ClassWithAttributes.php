<?php
namespace Apie\Fixtures\Other;

use Apie\Core\Attributes\Requires;

class ClassWithAttributes
{
    #[Requires('test')]
    public string $property;
}