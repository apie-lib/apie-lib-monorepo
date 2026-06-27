<?php
namespace Apie\IanaValueObjects\LanguageTag;

use Apie\Core\ValueObjects\NonEmptyString;
use Apie\IanaValueObjects\IsSanitizingInput;
use Apie\IanaValueObjects\StaticDataValueObject;
use DateTimeImmutable;

trait IsLanguageSubtag
{
    use StaticDataValueObject, IsSanitizingInput {
        IsSanitizingInput::convert insteadof StaticDataValueObject;
    }

    public function toPreferredValue(): static
    {
        $preferred = $this->getFieldValue('Preferred-Value');
        if ($preferred === null) {
            return $this;
        }
        return new static($preferred);
    }

    public function isActive(): bool
    {
        return (bool) $this->getFieldValue('Active');
    }

    public function getSubtag(): string
    {
        return $this->getFieldValue('Subtag');
    }

    public function getDescription(): ?NonEmptyString
    {
        $description = $this->getFieldValue('Description');
        return $description ? NonEmptyString::fromNative($description) : null;
    }

    public function getAdded(): ?DateTimeImmutable
    {
        $added = $this->getFieldValue('Added');
        return $added ? DateTimeImmutable::createFromFormat('Y-m-d', $added) : null;
    }

    public function getDeprecated(): ?DateTimeImmutable
    {
        $added = $this->getFieldValue('Deprecated');
        return $added ? DateTimeImmutable::createFromFormat('Y-m-d', $added) : null;
    }

    public function getPrefix(): ?NonEmptyString
    {
        $prefix = $this->getFieldValue('Prefix');
        return $prefix ? NonEmptyString::fromNative($prefix) : null;
    }

    public function getMacrolanguage(): ?NonEmptyString
    {
        $prefix = $this->getFieldValue('Macrolanguage');
        return $prefix ? NonEmptyString::fromNative($prefix) : null;
    }
}
