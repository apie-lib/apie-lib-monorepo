<?php
namespace Apie\Fixtures\Other;

use Apie\Core\Attributes\Not;
use Apie\Core\Attributes\Requires;
use Apie\Core\Attributes\RuntimeCheck;

class ClassWithAttributes
{
    #[RuntimeCheck(new Requires('test'))]
    public string $property;

    #[RuntimeCheck(new Requires('test'))]
    #[RuntimeCheck(new Requires('test2'))]
    public string $property2;

    #[RuntimeCheck(new Not(new Requires('test')))]
    public string $property3;
}
