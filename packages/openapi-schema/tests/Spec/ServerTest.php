<?php


namespace Apie\Tests\OpenapiSchema\Spec;

use Apie\CompositeValueObjects\Exceptions\MissingValueException;
use Apie\OpenapiSchema\Exceptions\MissingPlaceholderVariables;
use Apie\OpenapiSchema\Spec\Server;
use PHPUnit\Framework\TestCase;

class ServerTest extends TestCase
{
    /**
     * @dataProvider validInputProvider
     */
    public function testFromNative_valid_input(array $expected, $input)
    {
        $testItem = Server::fromNative($input);
        $this->assertEquals($expected, $testItem->toNative());
    }

    public function validInputProvider()
    {
        yield [
            ['url' => 'https://www.example-api.nl'],
            ['url' => 'https://www.example-api.nl'],
        ];
        $input = [
            'url' => 'https://application-name.{host}.nl/v{version}',
            'variables' => [
                'host' => ['default' => 'example'],
                'version' => ['default' => '2', 'enums' => ['1', '2']],
            ]
        ];
        yield [
            $input,
            $input
        ];
    }

    /**
     * @dataProvider invalidInputProvider
     */
    public function testFromNative_invalid_input(string $expectedExceptionClass, $input)
    {
        $this->expectException($expectedExceptionClass);
        Server::fromNative($input);
    }

    public function invalidInputProvider()
    {
        yield [MissingValueException::class, []];
        yield [
            MissingPlaceholderVariables::class,
            ['url' => 'https://application-name.{host}.nl/v{version}'],
        ];
    }
}