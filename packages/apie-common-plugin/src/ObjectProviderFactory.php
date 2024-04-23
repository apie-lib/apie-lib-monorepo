<?php
namespace Apie\ApieCommonPlugin;

final class ObjectProviderFactory
{
    private function __construct()
    {
    }

    public static function create(): ObjectProvider
    {
        return class_exists(AvailableApieObjectProvider::class)
            ? new AvailableApieObjectProvider()
            : new class extends ObjectProvider {};
    }
}
