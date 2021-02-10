<?php


namespace Apie\Tests\OpenapiSchema\Spec;

use Apie\OpenapiSchema\Exceptions\DefaultValueNotInEnum;
use Apie\OpenapiSchema\Spec\ServerVariable;
use Apie\TypeJuggling\Exceptions\MissingValueException;
use PHPUnit\Framework\TestCase;

class ServerVariableTest extends TestCase
{
    /**
     * @dataProvider validInputProvider
     */
    public function testFromNative_valid_input(array $expected, $input)
    {
        $testItem = ServerVariable::fromNative($input);
        $this->assertEquals($expected, $testItem->toNative());
    }

    public function validInputProvider()
    {
        yield [
            ['default' => ''],
            ['default' => ''],
        ];

        yield [
            ['default' => '', 'description' => 'Beschrijving'],
            ['default' => '', 'description' => 'Beschrijving'],
        ];

        yield [
            ['default' => 'salami', 'description' => 'Favo pizza', 'enums' => ['salami', 'ansjovis']],
            ['default' => 'salami', 'description' => 'Favo pizza', 'enums' => ['salami', 'ansjovis']],
        ];
    }

    /**
     * @dataProvider invalidInputProvider
     */
    public function testFromNative_invalid_input(string $expectedExceptionClass, $input)
    {
        $this->expectException($expectedExceptionClass);
        ServerVariable::fromNative($input);
    }

    public function invalidInputProvider()
    {
        yield [
            MissingValueException::class,
            [],
        ];
        yield [
            DefaultValueNotInEnum::class,
            [
                'default' => '',
                'enums' => ['Salami', 'Ansjovis'],
            ],
        ];
    }

    /**
     * @test
     */
    public function it_can_be_filled_in_with_immutability()
    {
        $testItem = new ServerVariable('alternate', null);
        $testItem2 = $testItem
            ->with('enums', ['default', 'alternate'])
            ->with('default', 'default');
        $this->assertEquals('default', $testItem2->getDefault());
        $this->assertEquals('alternate', $testItem->getDefault());
        $this->assertNull($testItem->getEnums());
        $this->assertEquals(['default', 'alternate'], $testItem2->getEnums());
    }

    /**
     * @test
     * @dataProvider consistentProvider
     */
    public function it_can_not_become_in_an_inconsistent_state(string $field, $value)
    {
        $testItem = new ServerVariable('default value', null, 'default value', 'alternate');
        $this->expectException(DefaultValueNotInEnum::class);
        $testItem->with($field, $value);
    }

    public function consistentProvider()
    {
        yield [
            'enums',
            ['default', 'alternate'],
        ];

        yield [
            'default',
            'pizza',
        ];
    }
}
