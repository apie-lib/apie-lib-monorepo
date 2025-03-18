<?php
namespace Apie\Tests\CommonValueObjects;

use Apie\CommonValueObjects\SemanticVersion;
use Apie\Core\RegexUtils;
use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\Fixtures\TestHelpers\TestWithOpenapiSchema;
use PHPUnit\Framework\TestCase;

class SemanticVersionTest extends TestCase
{
    use TestWithOpenapiSchema;
    use TestWithFaker;
    
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_works_with_schema_generator()
    {
        $this->runOpenapiSchemaTestForCreation(
            SemanticVersion::class,
            'SemanticVersion-post',
            [
                'type' => 'string',
                'format' =>  'semanticversion',
                'pattern' => RegexUtils::removeDelimiters(SemanticVersion::getRegularExpression()),
            ]
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_convert_to_an_application_version()
    {
        $testItem = new SemanticVersion('1.2.3-dev');
        $actual = $testItem->toApplicationVersion();
        $this->assertEquals('1.2.3', $actual->toNative());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(SemanticVersion::class);
    }
}
