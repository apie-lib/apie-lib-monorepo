<?php


namespace Apie\ObjectAccessNormalizer\Exceptions;

use Apie\ObjectAccessNormalizer\Errors\ErrorBag;

/**
 * Used nu ErrprBag and ValidationException to link errors.
 *
 * @see ErrorBag::addThrowable()
 * @see ValidationException::getErrorBag()
 *
 * @internal
 */
interface ErrorBagAwareException
{
    public function getErrorBag(): ErrorBag;
}
