<?php

namespace Apie\CommonValueObjects;

use Apie\ValueObjects\StringCaseInsensitiveEnumTrait;
use Apie\ValueObjects\ValueObjectInterface;

/**
 * Enum for http authentication schemas.
 *
 * @see https://www.iana.org/assignments/http-authschemes/http-authschemes.xhtml
 */
final class AuthenticationSchema implements ValueObjectInterface
{
    use StringCaseInsensitiveEnumTrait;

    const BASIC = 'Basic';

    const BEARER = 'Bearer';

    const DIGEST = 'Digest';

    const HOBA = 'HOBA';

    const MUTUAL = 'MUTUAL';

    const NEGOTIATE = 'Negotiate';

    const OAUTH = 'OAuth';

    const SCRAM_SHA1 = 'SCRAM-SHA-1';

    const SCRAM_SHA256 = 'SCRAM-SHA-256';

    const VAPID = 'vapid';
}
