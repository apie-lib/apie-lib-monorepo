<?php
namespace Apie\Core\Dto;

use Apie\Core\Enums\FileStreamType;
use Apie\Core\Utils\ConverterUtils;
use Attribute;
use ReflectionType;

#[Attribute(Attribute::TARGET_CLASS|Attribute::TARGET_METHOD|Attribute::TARGET_PROPERTY|Attribute::TARGET_PARAMETER)]
class CmsInputOption implements DtoInterface
{
    public function __construct(
        // for file stream inputs
        public readonly ?FileStreamType $streamType = null,
        // for date format inputs  a php date format string
        public readonly ?string $dateFormatMethod = null,
    ) {
    }

    public function forType(ReflectionType $type): array
    {
        $data = get_object_vars($this);
        $class = ConverterUtils::toReflectionClass($type);
        if ($class === null) {
            unset($data['dateFormatMethod']);
            return $data;
        }
        if ($this->dateFormatMethod) {
            $data['dateFormatMethod'] = $class->getMethod($this->dateFormatMethod)->invoke(null);
        }
        return $data;
    }
}