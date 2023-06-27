<?php
namespace Apie\LaravelApie\Wrappers\Security;

use Apie\Core\Context\ApieContext;
use Apie\Core\ContextBuilders\ContextBuilderInterface;

class UserAuthenticationContextBuilder implements ContextBuilderInterface
{
    public function process(ApieContext $context): ApieContext
    {
        $user = auth()->user();
        if ($user) {
            $context = $context->registerInstance($user);

            if ($user instanceof ApieUserDecorator) {
                // TODO: make ApieUserDecorator for Apie.
                $context = $context->withContext('authenticated', $user->getEntity());
            }
        }
        $token = csrf_token();
        if ($token) {
            $context = $context->registerInstance($token);
        }

        return $context;
    }
}