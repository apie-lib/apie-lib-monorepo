<?php
namespace Apie\Tests\TypescriptCodeBuilder\Lists;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\Typehints\IdentifierTypeDefinition;
use Apie\TypescriptCodeBuilder\Dto\Typehints\ObjectTypeDefinition;
use Apie\TypescriptCodeBuilder\Dto\TypescriptDeclaration;
use Apie\TypescriptCodeBuilder\Lists\ArgumentList;
use Apie\TypescriptCodeBuilder\Lists\CodeList;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifier;

class CodeListTest extends ObjectTestCase
{
    public function testSortReturnsTopologicallySortedNewList(): void
    {
        $dependency = new TypescriptDeclaration(
            new JavascriptIdentifier('Dependency'),
            new ObjectTypeDefinition(new ArgumentList())
        );
        $dependent = new TypescriptDeclaration(
            new JavascriptIdentifier('Dependent'),
            new IdentifierTypeDefinition(new JavascriptIdentifier('Dependency'))
        );
        $list = new CodeList([$dependent, $dependency]);

        $sorted = $list->sort();

        self::assertNotSame($list, $sorted);
        self::assertSame([$dependent, $dependency], $list->toArray());
        self::assertSame([$dependency, $dependent], $sorted->toArray());
    }

    public static function className(): string
    {
        return CodeList::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'array',
            'items' => [
                'oneOf' => [
                    ['$ref' => '#/components/schemas/TypescriptFileExpression-post'],
                    ['$ref' => '#/components/schemas/TypescriptTypeDeclaration-post'],
                ],
            ],
        ];
    }
}
