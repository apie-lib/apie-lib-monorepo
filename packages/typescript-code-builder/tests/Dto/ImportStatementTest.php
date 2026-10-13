<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\ImportStatement;
use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifier;
use PHPUnit\Framework\Attributes\Test;

class ImportStatementTest extends ObjectTestCase
{
    public static function className(): string
    {
        return ImportStatement::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'source' => ['type' => 'string', 'nullable' => false],
                'imports' => [
                    '$ref' => '#/components/schemas/JavascriptIdentifierList-post'
                ],
                'typeOnly' => ['type' => 'boolean', 'nullable' => false],
            ],
            'required' => ['source', 'imports'],
        ];
    }

    #[Test]
    public function regular_imports_render_in_typescript_and_javascript(): void
    {
        $type = new ImportStatement('./contents/es6/index', new JavascriptIdentifierList([new JavascriptIdentifier('createapi')]));

        $this->assertSame('import { createapi } from "./contents/es6/index";', $type->toTypescript());
        $this->assertSame('import { createapi } from "./contents/es6/index";', $type->toJavascript());
        $this->assertSame(['createapi'], array_map(
            static fn (JavascriptIdentifier $identifier): string => $identifier->toNative(),
            $type->providesDefinitions(false)->toArray(),
        ));
    }

    #[Test]
    public function type_imports_only_render_in_typescript(): void
    {
        $type = new ImportStatement('./somewhere', new JavascriptIdentifierList([new JavascriptIdentifier('classdef')]), true);

        $this->assertSame('import type { classdef } from "./somewhere";', $type->toTypescript());
        $this->assertSame('', $type->toJavascript());
    }
}
