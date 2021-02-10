<?php


namespace Apie\Tests\OpenapiSchema\Spec;

use Apie\OpenapiSchema\Spec\OAuthFlow;
use Apie\TypeJuggling\Exceptions\MissingValueException;
use PHPUnit\Framework\TestCase;

class OAuthFlowTest extends TestCase
{
    /**
     * @dataProvider validInputProvider
     */
    public function testFromNative_valid_input(array $expected, $input)
    {
        $testItem = OAuthFlow::fromNative($input);
        $this->assertEquals($expected, $testItem->toNative());
    }

    public function validInputProvider()
    {
        $input = [
            'authorizationUrl' => 'https://example.com/api/oauth/dialog',
            'scopes' => [
                'write:pets' => 'modify pets in your account',
                'read:pets' => 'read your pets',
            ]
        ];
        yield [$input, $input];
        $input = [
            'authorizationUrl' => 'https://example.com/api/oauth/dialog',
            'scopes' => [
                'write:pets' => 'modify pets in your account',
                'read:pets' => 'read your pets',
            ],
            'tokenUrl' => 'https://example.com/api/oauth/token',
        ];
        yield [$input, $input];
    }

    /**
     * @dataProvider invalidInputProvider
     */
    public function testFromNative_invalid_input(string $expectedExceptionClass, $input)
    {
        $this->expectException($expectedExceptionClass);
        OAuthFlow::fromNative($input);
    }

    public function invalidInputProvider()
    {
        yield [
            MissingValueException::class,
            []
        ];
    }
}
