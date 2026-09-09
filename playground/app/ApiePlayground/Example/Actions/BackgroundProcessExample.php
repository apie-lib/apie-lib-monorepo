<?php
namespace App\ApiePlayground\Example\Actions;

use Apie\Core\Attributes\Context;
use Apie\Core\BackgroundProcess\SequentialBackgroundProcess;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Datalayers\ApieDatalayer;
use Apie\Core\Lists\ItemHashmap;
use App\ApiePlayground\Example\BackgroundProcess\ExpensiveCalculation;

class BackgroundProcessExample
{
    public function doExpensiveCalculation(
        #[Context()] ApieDatalayer $apieDatalayer,
        int $number
    ): SequentialBackgroundProcess {

        $result = new SequentialBackgroundProcess(
            new ExpensiveCalculation(),
            new ItemHashmap(['number' => $number])
        );
        return $apieDatalayer->persistNew($result, new BoundedContextId('example'));
    }
}