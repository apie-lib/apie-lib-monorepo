<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\ExportStatement;
use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifier;
use PHPUnit\Framework\Attributes\Test;

class ExportStatementTest extends ObjectTestCase
{
    public static function className(): string
    {
        return ExportStatement::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'exports' => [
                    '$ref' => '#/components/schemas/JavascriptIdentifierList-post'
                ],
            ],
            'required' => ['exports'],
        ];
    }

    #[Test]
    public function it_renders_exports_and_tracks_dependencies(): void
    {
        $statement = new ExportStatement(new JavascriptIdentifierList([
            new JavascriptIdentifier('ApieLayer'),
            new JavascriptIdentifier('OtherLayer'),
        ]));

        $this->assertSame('export { ApieLayer, OtherLayer }', $statement->toTypescript());
        $this->assertSame($statement->toTypescript(), $statement->toJavascript());
        $this->assertSame(['ApieLayer', 'OtherLayer'], $statement->needsDefinitions()->toStringArray());
        $this->assertSame([], $statement->providesDefinitions(false)->toStringArray());
    }
}