<?php


namespace Apie\Tests\OpenapiSchema\Spec;

use Apie\OpenapiSchema\Exceptions\ApiKeySecuritySchemeRequiredFieldsMissing;
use Apie\OpenapiSchema\Exceptions\HttpSecuritySchemeRequiredFieldsMissing;
use Apie\OpenapiSchema\Exceptions\Oauth2SecuritySchemeRequiredFieldsMissing;
use Apie\OpenapiSchema\Exceptions\OpenIdSecuritySchemeRequiredFieldsMissing;
use Apie\OpenapiSchema\Spec\SecurityScheme;
use Apie\OpenapiSchema\ValueObjects\ParameterIn;
use Apie\OpenapiSchema\ValueObjects\SecuritySchemeType;
use Apie\TypeJuggling\Exceptions\MissingValueException;
use Apie\ValueObjects\Exceptions\InvalidValueForValueObjectException;
use PHPUnit\Framework\TestCase;

class SecuritySchemeTest extends TestCase
{
    /**
     * @dataProvider validInputProvider
     */
    public function testFromNative_valid_input(array $expected, $input)
    {
        $testItem = SecurityScheme::fromNative($input);
        $this->assertEquals($expected, $testItem->toNative());
    }

    public function validInputProvider()
    {
        yield [
            [
                'type' => 'http',
                'scheme' => 'Basic',
            ],
            [
                'type' => 'http',
                'scheme' => 'basic',
            ]
        ];

        $input = [
            'type' => 'apiKey',
            'name' => 'api_key',
            'in' => 'header',
        ];

        yield [$input, $input];

        $input = [
            'type' => 'http',
            'scheme' => 'Bearer',
            'bearerFormat' => 'JWT',
        ];
        yield [$input, $input];

        $input = [
            'type' => 'oauth2',
            'flows' => [
                'implicit' => [
                    'authorizationUrl' => 'https://example.com/api/oauth/dialog',
                    'scopes' => [
                        'write:pets' => 'modify pets in your account',
                        'read:pets' => 'read your pets',
                    ]
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
        SecurityScheme::fromNative($input);
    }

    public function invalidInputProvider()
    {
        yield [MissingValueException::class, []];
        yield [ApiKeySecuritySchemeRequiredFieldsMissing::class, ['type' => SecuritySchemeType::API_KEY]];
        yield [InvalidValueForValueObjectException::class, [
            'type' => SecuritySchemeType::API_KEY,
            'name' => '',
            'in' => ParameterIn::HEADER
        ]];
        yield [OpenIdSecuritySchemeRequiredFieldsMissing::class, ['type' => SecuritySchemeType::OPEN_ID_CONNECT]];
        yield [Oauth2SecuritySchemeRequiredFieldsMissing::class, ['type' => SecuritySchemeType::OAUTH2]];
        yield [HttpSecuritySchemeRequiredFieldsMissing::class, ['type' => SecuritySchemeType::HTTP]];
        yield [InvalidValueForValueObjectException::class, [
            'type' => SecuritySchemeType::API_KEY,
            'name' => 'George',
            'in' => 'Large box'
        ]];
        yield [MissingValueException::class, ['type' => SecuritySchemeType::HTTP, 'scheme' => 'bearer']];
        yield [InvalidValueForValueObjectException::class, ['type' => 'unknown']];
    }
}
