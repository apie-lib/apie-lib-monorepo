<?php
namespace Apie\CommonValueObjects\Exceptions;

use Apie\Core\Exceptions\ApieException;
use Apie\Core\ValueObjects\Utils;

class RangeMismatchException extends ApieException
{
    public function __construct(mixed $first, mixed $second)
    {
        parent::__construct(
            sprintf(
                '%s is higher than %s',
                Utils::displayMixedAsString($first),
                Utils::displayMixedAsString($second)
            )
        );
    }
}
