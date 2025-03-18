<?php
namespace Apie\Tests\Maker\CodeGenerators;

use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Identifiers\PascalCaseSlug;
use Apie\Maker\CodeGenerators\CreateDomainObject;
use Apie\Maker\Dtos\DomainObjectDto;
use Apie\Maker\Enums\IdType;
use Generator;
use PHPUnit\Framework\TestCase;

class CreateDomainObjectTest extends TestCase
{
    private function given_a_domain_object_generator(): CreateDomainObject
    {
        return new CreateDomainObject([
            'example' => [
                'entities_namespace' => 'Test\NamespaceExample\Resources',
                'entities_folder' => __DIR__ . '/../../fixtures/generated-domain-object/Resources',
            ],
            'replace' => [
                'entities_namespace' => 'Apie\Tests\Maker\Fixtures\Resources',
                'entities_folder' => __DIR__ . '/../Fixtures/Resources',
            ]
        ]);
    }
    #[\PHPUnit\Framework\Attributes\DataProvider('provideDomainObjectDtos')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_generate_code_for_a_domain_object(string $fixtureFile, DomainObjectDto $input)
    {
        $testItem = $this->given_a_domain_object_generator();
        $actual = $testItem->generateDomainObjectCode($input);
        $fixturePath = __DIR__ . '/../../fixtures/generated-domain-object/Resources/' . $fixtureFile;
        file_put_contents($fixturePath, $actual);
        $this->assertEquals(
            file_get_contents($fixturePath),
            $actual
        );
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('provideDomainObjectDtos')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_generate_code_for_a_domain_object_identifier(string $fixtureFile, DomainObjectDto $input)
    {
        $testItem = $this->given_a_domain_object_generator();
        $actual = $testItem->generateDomainIdentifierCode($input);
        $fixturePath = __DIR__ . '/../../fixtures/generated-domain-object/Identifiers/' . $fixtureFile;
        file_put_contents($fixturePath, $actual);
        $this->assertEquals(
            file_get_contents($fixturePath),
            $actual
        );
    }

    public static function provideDomainObjectDtos(): Generator
    {
        yield 'Simple example' => [
            'expected-example.phpinc',
            new DomainObjectDto(
                new PascalCaseSlug('Example'),
                new BoundedContextId('example'),
                IdType::Identifier,
                false
            )
        ];
        yield 'Existing class, overwrite' => [
            'expected-existing-class-overwrite.phpinc',
            new DomainObjectDto(
                new PascalCaseSlug('AlreadyExistingResource'),
                new BoundedContextId('replace'),
                IdType::Email,
                false
            )
        ];
        yield 'Existing class, modify existing' => [
            'expected-existing-class-with-modify.phpinc',
            new DomainObjectDto(
                new PascalCaseSlug('AlreadyExistingResource'),
                new BoundedContextId('replace'),
                IdType::Email,
                true
            )
        ];
    }
}
