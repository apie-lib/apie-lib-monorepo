<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Rules;

use Apie\Core\Attributes\ApieContextAttribute;
use Apie\Core\Context\ApieContext;
use Apie\Core\ContextConstants;
use Attribute;

/**
 * Runtime check that only allows the action if a user is logged in. Used to verify
 * that an authorization failure (ActionNotAllowedException) results in a 401/403
 * HTTP response instead of a 500 (see ErrorRenderTest).
 */
#[Attribute(Attribute::TARGET_METHOD)]
class RequiresAuthenticatedUser implements ApieContextAttribute
{
    public function applies(ApieContext $context): bool
    {
        return (bool) $context->getContext(ContextConstants::AUTHENTICATED_USER, false);
    }
}
