<?php


namespace Apie\Tests\OpenapiSchema\Spec;

use Apie\OpenapiSchema\Exceptions\OAuthRequiresAuthorizationUrl;
use Apie\OpenapiSchema\Exceptions\OAuthRequiresTokenUrl;
use Apie\OpenapiSchema\Spec\OAuthFlows;
use PHPUnit\Framework\TestCase;

class OAuthFlowsTest extends TestCase
{
    /**
     * @dataProvider validInputProvider
     */
    public function testFromNative_valid_input(array $expected, $input)
    {
        $testItem = OAuthFlows::fromNative($input);
        $this->assertEquals($expected, $testItem->toNative());
    }

    public function validInputProvider()
    {
        yield [[], []];
        $input = [
            'implicit' => [
                'authorizationUrl' => 'https://example.com/api/oauth/dialog',
                'scopes' => [
                    'write:pets' => 'modify pets in your account',
                    'read:pets' => 'read your pets',
                ]
            ],
            'authorizationCode' => [
                'authorizationUrl' => 'https://example.com/api/oauth/dialog',
                'tokenUrl' => 'https://example.com/api/oauth/token',
                'scopes' => [
                    'write:pets' => 'modify pets in your account',
                    'read:pets' => 'read your pets',
                ]
            ]
        ];
        yield [$input, $input];
    }

    /**
     * @dataProvider invalidInputProvider
     */
    public function testFromNative_invalid_input(string $expectedExceptionClass, $input)
    {
        $this->expectException($expectedExceptionClass);
        OAuthFlows::fromNative($input);
    }

    public function invalidInputProvider()
    {
        yield [
            OAuthRequiresAuthorizationUrl::class,
            ['implicit' => ['scopes' => []]]
        ];
        yield [
            OAuthRequiresTokenUrl::class,
            ['password' => ['scopes' => []]]
        ];
        yield [
            OAuthRequiresTokenUrl::class,
            ['clientCredentials' => ['scopes' => []]]
        ];
        yield [
            OAuthRequiresAuthorizationUrl::class,
            ['authorizationCode' => ['scopes' => []]]
        ];
    }
}
