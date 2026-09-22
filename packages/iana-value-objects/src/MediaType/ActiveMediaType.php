<?php
namespace Apie\IanaValueObjects\MediaType;

use Apie\Core\ValueObjects\Interfaces\LimitedOptionsInterface;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;
use Apie\IanaValueObjects\HasActiveFilter;

/**
 * All media types (MIME types) registered in the IANA Media Types Registry.
 *
 * @see https://www.iana.org/assignments/media-types/media-types.xhtml
 *
 * Only active media types can be used.
 */
final class ActiveMediaType implements StringValueObjectInterface, LimitedOptionsInterface
{
    use IsMediaType;
    use HasActiveFilter;
}
