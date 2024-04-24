<?php
namespace Apie\Maker\Concerns;

trait IsClassNameReference
{
    public static function getRegularExpression(): string
    {
        return '/^[A-Z][a-zA-Z0-9]*(\\[A-Z][a-zA-Z0-9]*)*$/';
    }
}
