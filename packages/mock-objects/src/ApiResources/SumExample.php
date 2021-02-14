<?php
namespace Apie\MockObjects\ApiResources;

use Apie\Core\Annotations\ApiResource;
use Apie\CorePlugin\DataLayers\NullDataLayer;

/**
 * @ApiResource(disabledMethods={"get"}, persistClass=NullDataLayer::class)
 */
class SumExample
{
    private $one;

    private $two;

    public function __construct(float $one, float $two)
    {
        $this->one = $one;
        $this->two = $two;
    }

    public function getAddition(): float
    {
        return $this->one + $this->two;
    }
}
