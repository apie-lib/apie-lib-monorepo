<?php
namespace Apie\Fixtures\ValueObjects;

use Apie\Core\ValueObjects\DatabaseText;
use Apie\Core\ValueObjects\SnowflakeIdentifier;

class SnowflakeExample extends SnowflakeIdentifier
{
    protected static function getSeparator(): string
    {
        return '|';
    }

    public function __construct(private ?DatabaseText $field, private Password $password)
    {
        $this->toNative();
    }

    public function getField(): ?DatabaseText
    {
        return $this->field;
    }

    public function getPassword(): Password
    {
        return $this->password;
    }
}