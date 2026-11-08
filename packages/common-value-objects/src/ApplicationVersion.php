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

    public function bumpPatch(): self
    {
        [$major, $minor, $patch] = explode('.', $this->internal);

        return new self(sprintf('%d.%d.%d', $major, $minor, (int) $patch + 1));
    }

    public function bumpMinor(): self
    {
        [$major, $minor] = explode('.', $this->internal);

        return new self(sprintf('%d.%d.%d', $major, (int) $minor + 1, 0));
    }

    public function bumpMajor(): self
    {
        [$major] = explode('.', $this->internal, 2);

        return new self(sprintf('%d.%d.%d', (int) $major + 1, 0, 0));
    }

    public function toSemanticVersion(string $suffix = ''): SemanticVersion
    {
        if ($suffix && false !== strpos('0123456789', substr($suffix, 0, 1))) {
            throw new \LogicException('Suffix can not start with a digit!');
        }
        return new SemanticVersion($this->internal . $suffix);
    }
}
