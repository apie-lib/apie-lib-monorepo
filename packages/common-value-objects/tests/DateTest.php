<?php

namespace Apie\Tests\CommonValueObjects;

use Apie\CommonValueObjects\Date;
use DateTime;
use PHPUnit\Framework\TestCase;

class DateTest extends TestCase
{
    protected function setUp(): void
    {
        Date::resetFormat();
    }

    protected function tearDown(): void
    {
        Date::resetFormat();
    }

    /**
     * @test
     */
    public function it_can_convert_from_and_to_a_string()
    {
        $testItem = Date::fromNative('2021-12-25');
        $this->assertEquals('2021-12-25', $testItem->toNative());
        Date::setFormat('d-m-Y');
        $this->assertEquals('25-12-2021', $testItem->toNative());
    }

    /**
     * @test
     */
    public function fromNative_accepts_datetime_objects()
    {
        $testItem = Date::fromNative(new DateTime('2021-12-25'));
        $this->assertEquals('2021-12-25', $testItem->toNative());
    }
}
