<?php
namespace Apie\Fixtures\Other;

use Apie\Core\Attributes\Not;
use Apie\Core\Attributes\Requires;

class ClassWithAttributes
{
    #[Requires('test')]
    public string $property;

    #[Requires('test')]
    #[Requires('test2')]
    public string $property2;

    #[Not(new Requires('test'))]
    public string $property3;
}
