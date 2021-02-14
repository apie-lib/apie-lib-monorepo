<?php
namespace Apie\Tests\StatusCheckPlugin\StatusChecks;

use Apie\StatusCheckPlugin\ApiResources\Status;
use Apie\StatusCheckPlugin\StatusChecks\StaticStatusCheck;
use PHPUnit\Framework\TestCase;

class StaticStatusCheckTest extends TestCase
{
    public function testGetters()
    {
        $testItem = new StaticStatusCheck(new Status('unit test'));
        $this->assertEquals(new Status('unit test'), $testItem->getStatus());
    }
}
