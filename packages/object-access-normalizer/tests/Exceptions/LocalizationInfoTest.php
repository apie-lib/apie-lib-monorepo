<?php


namespace Apie\Tests\ObjectAccessNormalizer\Exceptions;

use PHPUnit\Framework\TestCase;
use Apie\ObjectAccessNormalizer\Exceptions\LocalizationInfo;

class LocalizationInfoTest extends TestCase
{
    public function testConstruction()
    {
        $testItem = new LocalizationInfo('error', ['pizza' => 'has anchovy'], 2);
        $this->assertSame('error', $testItem->getMessageString());
        $this->assertEquals(['pizza' => 'has anchovy'], $testItem->getReplacements());
        $this->assertSame(2, $testItem->getAmount());
    }
}
