<?php
namespace Apie\Fixtures\ValueObjects;

use Apie\CompositeValueObjects\CompositeValueObject;
use Apie\Core\Attributes\Internal;
use Apie\Core\Attributes\Optional;
use Apie\Core\ValueObjects\Interfaces\ValueObjectInterface;

class CompositeValueObjectWithOptionalFields implements ValueObjectInterface
{
    use CompositeValueObject;

    private ?string $withDefaultValue = 'default value';

    #[Optional]
    private string $withOptionalAttribute;

    #[Internal]
    private ?string $isInternal = 'internal';

    private static ?string $isStatic = 'static';
}
