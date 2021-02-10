<?php


namespace Apie\Tests\ObjectAccessNormalizer\Exceptions;

use Apie\ObjectAccessNormalizer\Exceptions\LocalizationInfo;
use PHPUnit\Framework\TestCase;

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
