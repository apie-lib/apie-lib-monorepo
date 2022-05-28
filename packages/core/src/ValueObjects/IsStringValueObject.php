<?php
namespace Apie\Core\ValueObjects;

trait IsStringValueObject {
    private string $internal;
    public function __construct(string|int|float|bool|Stringable $input)
    {
        $input = $this->convert((string) $input);
        self::validate($input);
        $this->internal = $input;
    }

    public static function fromNative(array|string|int|float|bool|ValueObjectInterface $input): self
    {
        if ($input instanceof ValueObjectInterface) {
            $input = $input->toNative();
        }
        return new self($input);
    }
    public function toNative(): string
    {
        return $this->internal;
    }

    public function __toString(): string
    {
        return $this->toNative();
    }
    
    public static function validate(string $input): void
    {
    }

    abstract protected function convert(string $input): string;
}