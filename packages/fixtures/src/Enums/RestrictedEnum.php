<?php
namespace Apie\Fixtures\Enums;

use Apie\Core\Attributes\AllApplies;
use Apie\Core\Attributes\Equals;
use Apie\Core\Attributes\Requires;

enum RestrictedEnum: string
{
    #[Equals('locale', 'nl')]
    case RED = 'red';
    #[Requires('authenticated')]
    case GREEN = 'green';
    #[AllApplies(new Requires('authenticated'), new Equals('locale', 'nl'))]
    case BLUE = 'blue';
    #[Requires('authenticated')]
    #[Equals('locale', 'nl')]
    case ORANGE = 'orange';
}
