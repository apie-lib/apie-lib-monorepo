<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Actions;

use Apie\Core\Attributes\Context;
use Apie\Core\Attributes\Route;

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

    public function squareRoot(float $numberOne): float
    {
        return sqrt($numberOne);
    }
}
