<?php
namespace Apie\Fixtures\Actions;

use Apie\Fixtures\Lists\ImmutableStringOrIntList;

final class StaticActionExample
{
    private function __construct()
    {
    }

    public function secretCode(): ImmutableStringOrIntList
    {
        return new ImmutableStringOrIntList([1, 'A', '4251', '42', 42, 'This is a text']);
    }
}
