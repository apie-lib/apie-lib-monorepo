<?php
namespace Apie\IanaValueObjects\CharacterSet;

use Apie\Core\Lists\StringSet;
use Apie\Core\ValueObjects\NonEmptyString;
use Apie\Core\ValueObjects\Utils;

trait IsCharacterSet
{
    abstract protected function getFieldValue(string $fieldName): mixed;

    protected static function getData(): array
    {
        return require __DIR__ . '/../../fixtures/character-sets.php';
    }

    public function getPreferredMimeName(): string
    {
        return $this->getFieldValue('Preferred MIME Name');
    }

    public function getName(): string
    {
        return $this->getFieldValue('Name');
    }

    public function getMibEnum(): int
    {
        return Utils::toInt($this->getFieldValue('MIBenum'));
    }

    public function getSource(): string
    {
        return $this->getFieldValue('Source');
    }

    public function getReference(): ?NonEmptyString
    {
        $ref = $this->getFieldValue('Reference');
        return $ref ? NonEmptyString::fromNative($ref) : null;
    }

    public function getAliases(): StringSet
    {
        $aliases = $this->getFieldValue('Aliases');
        if (empty($aliases)) {
            return new StringSet();
        }

        return new StringSet(explode("\n", $aliases));
    }

    public function getNote(): ?NonEmptyString
    {
        $note = $this->getFieldValue('Note');
        return $note ? NonEmptyString::fromNative($note): null;
    }

    public function isActive(): bool
    {
        return (bool) $this->getFieldValue('Active');
    }
}
