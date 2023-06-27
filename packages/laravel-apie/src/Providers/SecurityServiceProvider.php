<?php
namespace Apie\LaravelApie\Providers;

use Apie\LaravelApie\Wrappers\Security\UserAuthenticationContextBuilder;
use Illuminate\Support\ServiceProvider;

class SecurityServiceProvider extends ServiceProvider
{
    public function register()
    {
        // sf variation: security.yaml
        $this->app->bind(UserAuthenticationContextBuilder::class);
        $this->app->tag(UserAuthenticationContextBuilder::class, ['apie.core.context_builder']);
    }
}