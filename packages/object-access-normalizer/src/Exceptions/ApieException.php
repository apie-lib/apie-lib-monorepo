<?php

namespace Apie\ObjectAccessNormalizer\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Base class that is extended by all Apie classes.
 */
abstract class ApieException extends HttpException
{
}
