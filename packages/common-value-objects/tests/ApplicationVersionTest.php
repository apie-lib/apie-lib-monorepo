<?php
namespace Apie\Tests\CommonValueObjects;

use Apie\CommonValueObjects\ApplicationVersion;
use Apie\Core\RegexUtils;
use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\Fixtures\TestHelpers\TestWithOpenapiSchema;
use PHPUnit\Framework\TestCase;

class ApplicationVersionTest extends TestCase
{
    use TestWithOpenapiSchema;
    use TestWithFaker;
    
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_works_with_schema_generator()
    {
        $this->runOpenapiSchemaTestForCreation(
            ApplicationVersion::class,
            'ApplicationVersion-post',
            [
                'type' => 'string',
                'format' =>  'applicationversion',
                'pattern' => RegexUtils::removeDelimiters(ApplicationVersion::getRegularExpression()),
            ]
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_convert_to_a_semantic_version()
    {
        $testItem = new ApplicationVersion('1.0.0');
        $actual = $testItem->toSemanticVersion();
        $this->assertEquals('1.0.0', $actual->toNative());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(ApplicationVersion::class);
    }
}
