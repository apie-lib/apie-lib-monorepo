<?php
namespace Apie\Fixtures\Enums;

use Apie\Core\Attributes\AllApplies;
use Apie\Core\Attributes\Equals;
use Apie\Core\Attributes\Requires;
use Apie\Core\Attributes\RuntimeCheck;

enum RestrictedEnum: string
{
    #[RuntimeCheck(new Equals('locale', 'nl'))]
    case RED = 'red';
    #[RuntimeCheck(new Requires('authenticated'))]
    case GREEN = 'green';
    #[RuntimeCheck(new AllApplies(new Requires('authenticated'), new Equals('locale', 'nl')))]
    case BLUE = 'blue';
    #[RuntimeCheck(new Requires('authenticated'))]
    #[RuntimeCheck(new Equals('locale', 'nl'))]
    case ORANGE = 'orange';
}
