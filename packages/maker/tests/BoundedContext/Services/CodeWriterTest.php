<?php
namespace Apie\Tests\Maker\BoundedContext\Services;

use Apie\Core\Identifiers\Identifier;
use Apie\Core\Identifiers\PascalCaseSlug;
use Apie\Core\Other\MockFileWriter;
use Apie\Maker\BoundedContext\Dtos\PropertyDefinition;
use Apie\Maker\BoundedContext\Identifiers\ResourceDefinitionIdentifier;
use Apie\Maker\BoundedContext\Interfaces\CodeWriterConfigurationInterface;
use Apie\Maker\BoundedContext\Lists\PropertyDefinitionList;
use Apie\Maker\BoundedContext\Resources\BoundedContextDefinition;
use Apie\Maker\BoundedContext\Resources\ResourceDefinition;
use Apie\Maker\BoundedContext\Services\CodeWriter;
use Apie\Maker\Enums\IdType;
use Apie\Maker\Enums\NullableOption;
use Apie\Maker\Enums\OverwriteStrategy;
use Apie\Maker\Enums\PrimitiveType;
use Apie\Maker\ValueObjects\PropertyDefinitionName;
use Apie\Tests\Maker\Fixtures\Identifiers\AlreadyExistingResourceIdentifier;
use Apie\Tests\Maker\Fixtures\MockCodeWriterConfiguration;
use Apie\Tests\Maker\Fixtures\Resources\AlreadyExistingResource;
use Generator;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class CodeWriterTest extends TestCase
{
    private const IDENTIFIER_FILE = '/app/Fixtures/Identifiers/AlreadyExistingResourceIdentifier.php';
    private const RESOURCE_FILE = '/app/Fixtures/Resources/AlreadyExistingResource.php';
    #[\PHPUnit\Framework\Attributes\DataProvider('provideFromExistingSource')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_generate_identifier_code_from_an_existing_code(
        string $expectedOutputFile,
        CodeWriterConfigurationInterface $log,
        ResourceDefinition $resourceDefinition,
        BoundedContextDefinition $boundedContextDefinition
    ): void {
        $fileWriter = $this->given_a_FileWriter_with_existing_code();
        $testItem = new CodeWriter($fileWriter);
        $testItem->writeIdentifier(
            $log,
            $resourceDefinition,
            $boundedContextDefinition
        );
        $expectedOutputFile = __DIR__ . '/../../../fixtures/code-writer/identifiers/' . $expectedOutputFile;
        $this->assertTrue(
            isset($fileWriter->writtenFiles[self::IDENTIFIER_FILE]),
            'I expect a file to exist, but found "' . implode('", "', array_keys($fileWriter->writtenFiles)) . '" instead'
        );
        // file_put_contents($expectedOutputFile, $fileWriter->writtenFiles[self::IDENTIFIER_FILE]);

        $this->assertEquals(
            file_get_contents($expectedOutputFile),
            $fileWriter->writtenFiles[self::IDENTIFIER_FILE]
        );
    }

    public static function provideFromExistingSource(): Generator
    {
        $boundedContextDefinition = new BoundedContextDefinition(new Identifier('fixtures'));
        $resourceDefinition = new ResourceDefinition(
            ResourceDefinitionIdentifier::createRandom(),
            IdType::UppercaseSlug,
            new PascalCaseSlug('AlreadyExistingResource'),
            $boundedContextDefinition->getId(),
            new PropertyDefinitionList([])
        );
        yield 'Reset ignores current files' => [
            'no-change.phpinc',
            new MockCodeWriterConfiguration(OverwriteStrategy::Reset, '/app', 'Apie\Tests\Maker'),
            $resourceDefinition,
            $boundedContextDefinition
        ];
        yield 'Overwrite ignores current files' => [
            'overwrite.phpinc',
            new MockCodeWriterConfiguration(OverwriteStrategy::Overwrite, '/app', 'Apie\Tests\Maker'),
            $resourceDefinition,
            $boundedContextDefinition
        ];
        yield 'Merge keeps current file methods' => [
            'merged.phpinc',
            new MockCodeWriterConfiguration(OverwriteStrategy::Merge, '/app', 'Apie\Tests\Maker'),
            $resourceDefinition,
            $boundedContextDefinition
        ];
        $resourceDefinition = new ResourceDefinition(
            ResourceDefinitionIdentifier::createRandom(),
            IdType::Ulid,
            new PascalCaseSlug('AlreadyExistingResource'),
            $boundedContextDefinition->getId(),
            new PropertyDefinitionList([])
        );
        yield 'Changing id type' => [
            'different-id-type-ulid.phpinc',
            new MockCodeWriterConfiguration(OverwriteStrategy::Merge, '/app', 'Apie\Tests\Maker'),
            $resourceDefinition,
            $boundedContextDefinition
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('provideForCodeWriter')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_generate_resource_code_from_an_existing_code(
        string $expectedOutputFile,
        CodeWriterConfigurationInterface $log,
        ResourceDefinition $resourceDefinition,
        BoundedContextDefinition $boundedContextDefinition
    ): void {
        $fileWriter = $this->given_a_FileWriter_with_existing_code();
        $testItem = new CodeWriter($fileWriter);
        $testItem->writeResource(
            $log,
            $resourceDefinition,
            $boundedContextDefinition
        );
        $expectedOutputFile = __DIR__ . '/../../../fixtures/code-writer/resources/' . $expectedOutputFile;
        $this->assertTrue(
            isset($fileWriter->writtenFiles[self::RESOURCE_FILE]),
            'I expect a file to exist, but found "' . implode('", "', array_keys($fileWriter->writtenFiles)) . '" instead'
        );
        // file_put_contents($expectedOutputFile, $fileWriter->writtenFiles[self::RESOURCE_FILE]);

        $this->assertEquals(
            file_get_contents($expectedOutputFile),
            $fileWriter->writtenFiles[self::RESOURCE_FILE]
        );
    }

    public static function provideForCodeWriter(): Generator
    {
        $boundedContextDefinition = new BoundedContextDefinition(new Identifier('fixtures'));
        $resourceDefinition = new ResourceDefinition(
            ResourceDefinitionIdentifier::createRandom(),
            IdType::UppercaseSlug,
            new PascalCaseSlug('AlreadyExistingResource'),
            $boundedContextDefinition->getId(),
            new PropertyDefinitionList([])
        );
        yield 'Reset ignores current files' => [
            'no-change.phpinc',
            new MockCodeWriterConfiguration(OverwriteStrategy::Reset, '/app', 'Apie\Tests\Maker'),
            $resourceDefinition,
            $boundedContextDefinition
        ];
        yield 'Overwrite ignores current files' => [
            'overwrite.phpinc',
            new MockCodeWriterConfiguration(OverwriteStrategy::Overwrite, '/app', 'Apie\Tests\Maker'),
            $resourceDefinition,
            $boundedContextDefinition
        ];
        yield 'Merge keeps current file methods' => [
            'merged.phpinc',
            new MockCodeWriterConfiguration(OverwriteStrategy::Merge, '/app', 'Apie\Tests\Maker'),
            $resourceDefinition,
            $boundedContextDefinition
        ];
        $resourceDefinition = new ResourceDefinition(
            ResourceDefinitionIdentifier::createRandom(),
            IdType::Ulid,
            new PascalCaseSlug('AlreadyExistingResource'),
            $boundedContextDefinition->getId(),
            new PropertyDefinitionList([])
        );
        yield 'Changing id type to an ulid' => [
            'different-id-type-ulid.phpinc',
            new MockCodeWriterConfiguration(OverwriteStrategy::Merge, '/app', 'Apie\Tests\Maker'),
            $resourceDefinition,
            $boundedContextDefinition
        ];
        $resourceDefinition = new ResourceDefinition(
            ResourceDefinitionIdentifier::createRandom(),
            IdType::Integer,
            new PascalCaseSlug('AlreadyExistingResource'),
            $boundedContextDefinition->getId(),
            new PropertyDefinitionList([])
        );
        yield 'Changing id type to an auto increment integer' => [
            'different-id-type-autoincrement.phpinc',
            new MockCodeWriterConfiguration(OverwriteStrategy::Merge, '/app', 'Apie\Tests\Maker'),
            $resourceDefinition,
            $boundedContextDefinition
        ];
        $resourceDefinition = new ResourceDefinition(
            ResourceDefinitionIdentifier::createRandom(),
            IdType::Identifier,
            new PascalCaseSlug('AlreadyExistingResource'),
            $boundedContextDefinition->getId(),
            new PropertyDefinitionList([])
        );
        yield 'Changing id type to an identifier' => [
            'different-id-type-identifier.phpinc',
            new MockCodeWriterConfiguration(OverwriteStrategy::Merge, '/app', 'Apie\Tests\Maker'),
            $resourceDefinition,
            $boundedContextDefinition
        ];

        $propertyList = [];
        foreach (NullableOption::cases() as $nullableOption) {
            foreach ([true, false] as $requiredOnConstruction) {
                foreach ([true, false] as $writable) {
                    foreach ([true, false] as $readable) {
                        $propertyName = lcfirst($nullableOption->name)
                            . ($requiredOnConstruction ? 'Constructor' : '')
                            . ($writable ? 'Writable' : '')
                            . ($readable ? 'Readable' : '');
                        $propertyList[] = new PropertyDefinition(
                            PrimitiveType::String,
                            new PropertyDefinitionName($propertyName),
                            $requiredOnConstruction,
                            $writable,
                            $readable,
                            $nullableOption
                        );
                    }
                }
            }
        }
        $resourceDefinition = new ResourceDefinition(
            ResourceDefinitionIdentifier::createRandom(),
            IdType::UppercaseSlug,
            new PascalCaseSlug('AlreadyExistingResource'),
            $boundedContextDefinition->getId(),
            new PropertyDefinitionList($propertyList)
        );
        yield 'Adding many properties' => [
            'add-many-properties.phpinc',
            new MockCodeWriterConfiguration(OverwriteStrategy::Merge, '/app', 'Apie\Tests\Maker'),
            $resourceDefinition,
            $boundedContextDefinition
        ];
    }

    private function given_a_FileWriter_with_existing_code(): MockFileWriter
    {
        $fileWriter = new MockFileWriter();
        $originalIdSourceCode = file_get_contents(
            (new ReflectionClass(AlreadyExistingResourceIdentifier::class))->getFileName()
        );
        $originalResourceSourceCode = file_get_contents(
            (new ReflectionClass(AlreadyExistingResource::class))->getFileName()
        );
        $fileWriter->writtenFiles = [
            self::IDENTIFIER_FILE => $originalIdSourceCode,
            self::RESOURCE_FILE => $originalResourceSourceCode,
        ];
        return $fileWriter;
    }
}
