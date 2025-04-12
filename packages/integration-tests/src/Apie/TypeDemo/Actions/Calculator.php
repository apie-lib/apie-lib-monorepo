<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Actions;

use Apie\Core\Attributes\Context;
use Apie\Core\Attributes\Route;
use Apie\Core\BackgroundProcess\SequentialBackgroundProcess;
use Apie\Core\Lists\ItemHashmap;
use Apie\Fixtures\BackgroundProcess\SequentialExample;

final class Calculator
{
    #[Route('/calc/{numberOne}/plus/{numberTwo}')]
    public function add(#[Context()] float $numberOne, #[Context()] float $numberTwo): float
    {
        return $numberOne + $numberTwo;
    }

    #[Route('/calc/{numberOne}/times/{numberTwo}')]
    public function multiply(#[Context()] float $numberOne, #[Context()] float $numberTwo): float
    {
        return $numberOne * $numberTwo;
    }

    public function sum(float... $numbers): float
    {
        return array_sum($numbers);
    }

    public function squareRoot(float $numberOne): float
    {
        return sqrt($numberOne);
    }

    public function expensiveBackgroundCalculation(int $payload)
    {
        return new SequentialBackgroundProcess(
            new SequentialExample(),
            new ItemHashmap(['payload' => $payload])
        );
    }
}
