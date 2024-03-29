<?php

namespace Apie\CommonValueObjects;

use Apie\Core\Attributes\FakeMethod;
use Apie\Core\Attributes\SchemaMethod;
use Apie\Core\Exceptions\InvalidTypeException;
use Apie\Core\ValueObjects\Interfaces\ValueObjectInterface;
use Apie\Core\ValueObjects\Utils;
use Faker\Generator;

#[FakeMethod('createRandom')]
#[SchemaMethod('provideSchema')]
final class StarRating implements ValueObjectInterface
{
    public function __construct(
        private int $input
    ) {
        if ($input < 0 || $input > 5) {
            throw new InvalidTypeException($input, '0-5');
        }
    }

    public static function createRandom(Generator $faker): self
    {
        return new self($faker->numberBetween(0, 5));
    }
    public static function fromNative(mixed $input): self
    {
        return new StarRating(Utils::toInt($input));
    }

    /**
     * @return array<string, string|int>
     */
    public static function provideSchema(): array
    {
        return [
            'type' => 'number',
            'format' => 'integer',
            'minimum' => 0,
            'maximum' => 5,
        ];
    }

    public function toNative(): int
    {
        return $this->input;
    }
}
