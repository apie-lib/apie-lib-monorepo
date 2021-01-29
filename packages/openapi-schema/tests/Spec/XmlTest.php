<?php


namespace Apie\Tests\OpenapiSchema\Spec;


use Apie\CommonValueObjects\KebabCaseString;
use Apie\OpenapiSchema\Spec\Xml;
use Apie\ValueObjects\Exceptions\InvalidValueForValueObjectException;
use PHPUnit\Framework\TestCase;

class XmlTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_filled_in_with_immutability()
    {
        $testItem = (new Xml())
            ->with('name', 'books')
            ->with('wrapped', true);
        $this->assertEquals(new KebabCaseString('books'), $testItem->getName());
        $this->assertTrue($testItem->isWrapped());
        $testItem2 = $testItem
            ->with('name', 'tests');
        $this->assertEquals(new KebabCaseString('books'), $testItem->getName());
        $this->assertEquals(new KebabCaseString('tests'), $testItem2->getName());
        $this->assertNull($testItem2->getNamespace());
        $this->assertNull($testItem2->getPrefix());
        $this->assertFalse($testItem2->isAttribute());
        $this->assertTrue($testItem2->isWrapped());
    }

    /**
     * @dataProvider validInputProvider
     */
    public function testFromNative_valid_input(array $expected, $input)
    {
        $testItem = Xml::fromNative($input);
        $this->assertEquals($expected, $testItem->toNative());
    }

    public function validInputProvider()
    {
        yield [
            [],
            []
        ];
        yield [
            [
                'name' => 'element-wrapper',
                'attribute' => true,
            ],
            [
                'name' => 'element-wrapper',
                'attribute' => true,
            ]
        ];

        yield [
            [
                'x-extra' => [],
            ],
            [
                'x-extra' => [],
            ]
        ];
    }

    /**
     * @dataProvider invalidInputProvider
     */
    public function testFromNative_invalid_input(string $expectedExceptionClass, $input)
    {
        $this->expectException($expectedExceptionClass);
        Xml::fromNative($input);
    }

    public function invalidInputProvider()
    {
        yield [
            InvalidValueForValueObjectException::class,
            [
                'name' => 'if (true) { echo "hi"; }'
            ]
        ];
        yield [
            InvalidValueForValueObjectException::class,
            [
                'namespace' => '/sparta/300'
            ]
        ];
    }
}