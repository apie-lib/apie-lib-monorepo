<?php
namespace Apie\RegexValueObjects\Exceptions;

use Apie\Core\Exceptions\ApieException;
use Apie\Core\ValueObjects\Utils;

class ExpressionContainsLookAheads extends ApieException
{
    public function __construct(mixed $input)
    {
        parent::__construct(
            sprintf(
                '%s is a valid regular expression but contains look-aheads and/or look-behinds',
                Utils::displayMixedAsString($input)
            )
        );
    }
}
