<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\RawJavascript;
use Apie\TypescriptCodeBuilder\Dto\VariableAssignment;
use Apie\TypescriptCodeBuilder\Enums\TypescriptType;
use Apie\TypescriptCodeBuilder\Enums\VariableDeclarationKind;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifier;

class VariableAssignmentTest extends ObjectTestCase
{
    public static function className(): string
    {
        return VariableAssignment::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'kind' => ['$ref' => '#/components/schemas/VariableDeclarationKind-post'],
                'name' => ['$ref' => '#/components/schemas/JavascriptIdentifier-post'],
                'expression' => ['$ref' => '#/components/schemas/TypescriptFileExpression-post'],
                'typehint' => ['$ref' => '#/components/schemas/TypescriptTypeDeclaration-nullable-post'],
            ],
            'required' => ['kind', 'name', 'expression'],
        ];
    }

    public function test_renders_typescript_and_javascript_assignments(): void
    {
        $type = TypescriptType::Number;

        $let = new VariableAssignment(
            VariableDeclarationKind::Let,
            new JavascriptIdentifier('a'),
            new RawJavascript('12'),
            $type,
        );
        $const = new VariableAssignment(
            VariableDeclarationKind::Const,
            new JavascriptIdentifier('b'),
            new RawJavascript('12'),
            $type,
        );
        $var = new VariableAssignment(
            VariableDeclarationKind::Var,
            new JavascriptIdentifier('d'),
            new RawJavascript('e()'),
            $type,
        );
        $noType = new VariableAssignment(
            VariableDeclarationKind::Var,
            new JavascriptIdentifier('d'),
            new RawJavascript('e()'),
            null,
        );

        $this->assertSame('let a: number = 12;', $let->toTypescript());
        $this->assertSame('const b: number = 12;', $const->toTypescript());
        $this->assertSame('var d: number = e();', $var->toTypescript());
        $this->assertSame('var d = e();', $noType->toTypescript());
        $this->assertSame('let a = 12;', $let->toJavascript());
        $this->assertSame('const b = 12;', $const->toJavascript());
        $this->assertSame('var d = e();', $var->toJavascript());
        $this->assertSame('var d = e();', $noType->toJavascript());
    }
}