<?php
namespace Apie\Core\Attributes;

use Apie\Core\RegexUtils;
use Apie\Core\ValueObjects\Utils;
use Attribute;
use ReflectionClass;

#[Attribute(Attribute::TARGET_CLASS|Attribute::TARGET_METHOD|Attribute::TARGET_PROPERTY|Attribute::TARGET_PARAMETER)]
final class CmsValidationCheck
{
    public function __construct(
        public string $message,
        public bool $inverseCheck = false,
        public readonly ?string $patternMethod = null,
        public readonly ?string $minLengthMethod = null,
        public readonly ?string $maxLengthMethod = null,
    ) {
    }

    /**
     * @param ReflectionClass<object> $class
     */
    public function toArray(ReflectionClass $class): array
    {
        $res = [
            'message' => $this->message,
            'inverseCheck' => $this->inverseCheck,
        ];
        foreach (get_object_vars($this) as $propertyName => $propertyValue) {
            if (str_ends_with($propertyName, 'Method') && is_string($propertyValue)) {
                $method = $class->getMethod($propertyValue);
                $res[preg_replace('/Method$/', '', $propertyName)] = $this->sanitize(
                    $propertyName,
                    $method->invoke(null)
                );
            }
        }
        return $res;
    }

    private function sanitize(string $propertyName, mixed $value)
    {
        if ($propertyName === 'patternMethod') {
            return RegexUtils::removeDelimiters(Utils::toString($value));
        }
        return $value;
    }
}
