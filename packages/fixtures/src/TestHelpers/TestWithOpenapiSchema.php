<?php
namespace Apie\Fixtures\TestHelpers;

use Apie\SchemaGenerator\ComponentsBuilderFactory;
use cebe\openapi\spec\Schema;

/** @codeCoverageIgnore */
trait TestWithOpenapiSchema
{
    public function runOpenapiSchemaTestForCreation(string $classToTest, string $expectedKey, Schema $expected, ?callable $testCase = null, ?ComponentsBuilderFactory $factory = null)
    {
        if (!class_exists(ComponentsBuilderFactory::class)) {
            $this->markTestIncomplete('Schema generator library not loaded, so skipping test');
            return;
        }
        $testCase ??= function () {
        };
        if (!$factory) {
            $factory = ComponentsBuilderFactory::createComponentsBuilderFactory();
        }
        $builder = $factory->createComponentsBuilder();
        $builder->addCreationSchemaFor($classToTest);
        $components = $builder->getComponents();
        $schemas = $components->schemas;
        $this->assertNotEmpty($schemas);
        $this->assertArrayHasKey($expectedKey, $schemas);
        $actualSchema = $schemas[$expectedKey];
        if ($expected->pattern) {
            $expected->pattern = $actualSchema->pattern;
        }
        $this->assertEquals($expected, $actualSchema);
        $testCase($builder);
    }
}
