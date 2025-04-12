<?php
namespace Apie\Fixtures\Actions;

use Apie\Core\BackgroundProcess\SequentialBackgroundProcess;
use Apie\Core\Lists\ItemHashmap;
use Apie\Fixtures\BackgroundProcess\SequentialExample;

class BackgroundProcessExample
{
    public function createBackgroundProcess(int $payload): SequentialBackgroundProcess
    {
        return new SequentialBackgroundProcess(
            new SequentialExample(),
            new ItemHashmap(['payload' => $payload])
        );
    }
}
