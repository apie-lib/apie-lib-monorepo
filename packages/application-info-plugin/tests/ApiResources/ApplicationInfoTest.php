<?php
namespace Apie\Tests\ApplicationInfoPlugin\ApiResources;

use Apie\ApplicationInfoPlugin\ApiResources\ApplicationInfo;
use PHPUnit\Framework\TestCase;

class ApplicationInfoTest extends TestCase
{
    public function testGetters()
    {
        $testItem = new ApplicationInfo('Unittest app', 'testing', '123456', true);
        $this->assertEquals('Unittest app', $testItem->getAppName());
        $this->assertEquals('testing', $testItem->getEnvironment());
        $this->assertEquals('123456', $testItem->getHash());
        $this->assertEquals(true, $testItem->isDebug());
    }
}
