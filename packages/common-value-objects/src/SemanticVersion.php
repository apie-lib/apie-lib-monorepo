<?php
namespace Apie\CommonValueObjects;

use Apie\Core\Attributes\FakeMethod;
use Apie\Core\ValueObjects\Interfaces\HasRegexValueObjectInterface;
use Apie\Core\ValueObjects\IsStringWithRegexValueObject;
use Faker\Generator;

#[FakeMethod('createRandom')]
class SemanticVersion implements HasRegexValueObjectInterface
{
    use IsStringWithRegexValueObject;

    public static function createRandom(Generator $generator): self
    {
        return new static($generator->semver(true, true));
    }

    protected function convert(string $input): string
    {
        return trim($input);
    }

    public function toApplicationVersion(): ApplicationVersion
    {
        preg_match('/^(0|[1-9]\d*)\.(0|[1-9]\d*)\.(0|[1-9]\d*)/', $this->internal, $matches);
        assert(!empty($matches));
        return new ApplicationVersion($matches[1] . '.' . $matches[2] . '.' . $matches[3]);
    }

    /**
     * @see https://semver.org/spec/v2.0.0.html
     */
    public static function getRegularExpression(): string
    {
        return '/^(0|[1-9]\d*)\.(0|[1-9]\d*)\.(0|[1-9]\d*)(?:-((?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*)(?:\.(?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*))*))?(?:\+([0-9a-zA-Z-]+(?:\.[0-9a-zA-Z-]+)*))?$/';
    }
}
