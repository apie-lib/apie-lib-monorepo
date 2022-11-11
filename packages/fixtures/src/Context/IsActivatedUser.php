<?php
namespace Apie\Fixtures\Context;

use Apie\Core\Attributes\ApieContextAttribute;
use Apie\Core\Context\ApieContext;
use Apie\Fixtures\Entities\UserWithAddress;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class IsActivatedUser implements ApieContextAttribute
{
    public function applies(ApieContext $context): bool
    {
        if (!$context->hasContext('authenticated')) {
            return false;
        }
        $user = $context->getContext('authenticated');
        var_dump($user);
        if ($user instanceof UserWithAddress) {
            return $user->hasPassword();
        }

        return false;
    }
}