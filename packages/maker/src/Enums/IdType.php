<?php
namespace Apie\Maker\Enums;

use Apie\CommonValueObjects\Email;
use Apie\Core\Identifiers\AutoIncrementInteger;
use Apie\Core\Identifiers\Identifier;
use Apie\Core\Identifiers\UuidV4;

enum IdType: string
{
    case Uuid = UuidV4::class;
    case Slug = Identifier::class;
    case Email = Email::class;
    case Integer = AutoIncrementInteger::class;

    public static function tryFromName(string $name): ?static
    {
        foreach (self::cases() as $case) {
            if ($case->name === $name || $case->value === $name) {
                return $case;
            }
        }

        return null;
    }
}
