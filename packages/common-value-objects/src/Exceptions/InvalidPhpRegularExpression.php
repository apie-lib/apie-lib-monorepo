<?php
namespace Apie\CommonValueObjects\Exceptions;

use Apie\Core\Exceptions\ApieException;
use Apie\Core\ValueObjects\Utils;

class InvalidPhpRegularExpression extends ApieException
{
    public function __construct(mixed $input, string $errorMessage)
    {
        parent::__construct(
            sprintf(
                '%s is not a valid regular expression. Error message: %s',
                Utils::displayMixedAsString($input),
                $errorMessage
            )
        );
    }
}
