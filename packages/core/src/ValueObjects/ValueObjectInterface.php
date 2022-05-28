<?php
namespace Apie\Core\ValueObjects;

interface ValueObjectInterface {
    public static function fromNative(array|string|int|float|bool|ValueObjectInterface $input): self;
    public function toNative(): array|string|int|float|float|bool
}
