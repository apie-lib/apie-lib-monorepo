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
                'description' => true,
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
    public function it_can_bump_the_patch_version()
    {
        $testItem = new ApplicationVersion('1.2.3');
        $actual = $testItem->bumpPatch();
        $this->assertEquals('1.2.4', $actual->toNative());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_bump_the_minor_version()
    {
        $testItem = new ApplicationVersion('1.2.3');
        $actual = $testItem->bumpMinor();
        $this->assertEquals('1.3.0', $actual->toNative());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_bump_the_major_version()
    {
        $testItem = new ApplicationVersion('1.2.3');
        $actual = $testItem->bumpMajor();
        $this->assertEquals('2.0.0', $actual->toNative());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_not_convert_to_a_semantic_version_with_a_digit_suffix()
    {
        $testItem = new ApplicationVersion('1.0.0');
        $this->expectExceptionMessage('Suffix can not start with a digit!');
        $testItem->toSemanticVersion('12');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(ApplicationVersion::class);
    }
}
