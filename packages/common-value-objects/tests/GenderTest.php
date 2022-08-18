<?php
namespace Apie\Tests\CommonValueObjects;

use Apie\CommonValueObjects\Gender;
use PHPUnit\Framework\TestCase;

class GenderTest extends TestCase
{
    public function testGetSalutation()
    {
        $this->assertEquals('Mr.', Gender::MALE->getSalutation());
        $this->assertEquals('Mrs.', Gender::FEMALE->getSalutation());
    }
}
