<?php
namespace Apie\Fixtures;

use Apie\Core\BoundedContext\BoundedContext;
use Apie\Core\BoundedContext\BoundedContextHashmap;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Lists\ReflectionClassList;
use Apie\Core\Lists\ReflectionMethodList;
use Apie\Fixtures\Actions\StaticActionExample;
use Apie\Fixtures\Entities\UserWithAddress;
use ReflectionClass;
use ReflectionMethod;

final class BoundedContextFactory
{
    private function __construct()
    {
    }

    public static function createHashmap(): BoundedContextHashmap
    {
        return new BoundedContextHashmap([
            'default' => self::createExample(),
        ]);
    }

    public static function createExample(): BoundedContext
    {
        return new BoundedContext(
            new BoundedContextId('default'),
            new ReflectionClassList([
                new ReflectionClass(UserWithAddress::class)
            ]),
            new ReflectionMethodList([
                new ReflectionMethod(StaticActionExample::class, 'secretCode'),
            ])
        );
    }
}
