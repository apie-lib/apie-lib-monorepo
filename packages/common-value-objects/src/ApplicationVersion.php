<?php
namespace Apie\CommonValueObjects;

use Apie\Core\Attributes\Description;
use Apie\Core\ValueObjects\Interfaces\HasRegexValueObjectInterface;
use Apie\Core\ValueObjects\IsStringWithRegexValueObject;

#[Description('A semantic version without any suffix, for example "1.0.0"')]
class ApplicationVersion implements HasRegexValueObjectInterface
{
    use IsStringWithRegexValueObject;

    protected function convert(string $input): string
    {
        return trim($input);
    }

    public static function getRegularExpression(): string
    {
        return '/^[1-9]*[0-9]\.[1-9]*[0-9]\.[1-9]*[0-9]$/';
    }

    public function toSemanticVersion(): SemanticVersion
    {
        return new SemanticVersion($this->internal);
    }
}
