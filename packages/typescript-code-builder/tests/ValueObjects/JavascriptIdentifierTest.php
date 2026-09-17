<?php
namespace Apie\Tests\TypescriptCodeBuilder\ValueObjects;

use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\Fixtures\TestHelpers\TestWithOpenapiSchema;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifier;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class JavascriptIdentifierTest extends TestCase
{
    use TestWithFaker;
    use TestWithOpenapiSchema;

    #[DataProvider('validProvider')]
    #[Test]
    public function it_allows_javascript_identifier_names(string $input): void
    {
        $this->assertSame($input, (new JavascriptIdentifier($input))->toNative());
        $this->assertSame($input, JavascriptIdentifier::fromNative($input)->toNative());
    }

    public static function validProvider(): array
    {
        return [
            ['lowercase'],
            ['UpperCase'],
            ['with_underscore'],
            ['value42'],
            ['_privateValue'],
        ];
    }

    #[DataProvider('invalidProvider')]
    #[Test]
    public function it_rejects_invalid_or_reserved_names(string $input): void
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        new JavascriptIdentifier($input);
    }

    public static function invalidProvider(): array
    {
        return [
            ['42value'],
            ['kebab-case'],
            ['with space'],
            ['class'],
            ['await'],
            ['return'],
        ];
    }

    #[Test]
    public function it_works_with_apie_faker(): void
    {
        $this->runFakerTest(JavascriptIdentifier::class);
    }

    #[Test]
    public function it_works_with_schema_generator(): void
    {
        $this->runOpenapiSchemaTestForCreation(
            JavascriptIdentifier::class,
            'JavascriptIdentifier-post',
            [
                'type' => 'string',
                'format' => true,
                'pattern' => true,
                'description' => true,
            ]
        );
    }
}