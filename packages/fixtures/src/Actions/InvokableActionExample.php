<?php
namespace Apie\Fixtures\Actions;

final class InvokableActionExample
{
    public function __invoke(int $number): int
    {
        return $number;
    }
}
