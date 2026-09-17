<?php
namespace Apie\TypescriptCodeBuilder\Dto\Typehints;

use Apie\Core\Attributes\FakeMethod;
use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\Enums\TypescriptType;
use Apie\TypescriptCodeBuilder\TypescriptTypeDeclarationInterface;
use Faker\Generator;

/**
 * Typescript definition for an array type.
 */
#[FakeMethod('createRandom')]
class ArrayTypeDefinition implements TypescriptTypeDeclarationInterface
{
    public function __construct(
        public TypescriptTypeDeclarationInterface $typehint
    ) {

    }
    public function toTypescript(): string
    {
        return $this->typehint->toTypescript() . '[]';
    }
    public function toJavascript(): string
    {
        return '';
    }
    public function providesDefinitions(): JavascriptIdentifierList
    {
        return new JavascriptIdentifierList();
    }
    public function needsDefinitions(): JavascriptIdentifierList
    {
        return $this->typehint->needsDefinitions();
    }

    public static function createRandom(Generator $faker): static
    {
        return new ArrayTypeDefinition($faker->fakeClass(TypescriptType::class));
    }
}
