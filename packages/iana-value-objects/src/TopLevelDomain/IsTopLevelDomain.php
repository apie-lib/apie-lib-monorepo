<?php
namespace Apie\IanaValueObjects\TopLevelDomain;

use Apie\IanaValueObjects\IsSanitizingInput;
use Apie\IanaValueObjects\StaticDataValueObject;

trait IsTopLevelDomain
{
    use StaticDataValueObject, IsSanitizingInput {
        IsSanitizingInput::convert insteadof StaticDataValueObject;
    }

    protected static function getData(): array
    {
        return require __DIR__ . '/../../fixtures/tlds.php';
    }

    public function isActive(): bool
    {
        return (bool) $this->getFieldValue('Active');
    }
}
