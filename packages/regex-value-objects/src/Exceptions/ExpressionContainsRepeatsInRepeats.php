<?php
namespace Apie\RegexValueObjects\Exceptions;

use Apie\Core\Exceptions\ApieException;
use Apie\Core\ValueObjects\Utils;

class ExpressionContainsRepeatsInRepeats extends ApieException
{
    public function __construct(mixed $input)
    {
        parent::__construct(
            sprintf(
                '%s is a valid regular expression but contains nested repetitions',
                Utils::displayMixedAsString($input)
            )
        );
    }
}
