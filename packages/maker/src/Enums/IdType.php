<?php
namespace Apie\Maker\Enums;

use Apie\CommonValueObjects\Email;
use Apie\Core\Identifiers\AutoIncrementInteger;
use Apie\Core\Identifiers\Identifier;
use Apie\Core\Identifiers\Ulid;
use Apie\Core\Identifiers\UuidV4;
use Nette\PhpGenerator\Parameter;
use Nette\PhpGenerator\PromotedParameter;

enum IdType: string
{
    case Uuid = UuidV4::class;
    case Slug = Identifier::class;
    case Email = Email::class;
    case Integer = AutoIncrementInteger::class;
    case Ulid = Ulid::class;

    public static function tryFromName(string $name): ?static
    {
        foreach (self::cases() as $case) {
            if ($case->name === $name || $case->value === $name) {
                return $case;
            }
        }

        return null;
    }

    public function toConstructorArgument(string $type): PromotedParameter|Parameter|null
    {
        return match($this) {
            self::Uuid, self::Ulid => (new Parameter('id'))->setType('?' . $type)->setDefaultValue(null),
            self::Slug, self::Email => (new PromotedParameter('id'))->setType($type),
            default => null,
        };
    }

    public function toConstructorBody(string $type): string
    {
        return match($this) {
            self::Integer => '$this->id = new ' . $type . '(null);',
            self::Uuid, self::Ulid => '$this->id = $id ?? ' . $type . '::createRandom();',
            default => '',
        };
    }
}
