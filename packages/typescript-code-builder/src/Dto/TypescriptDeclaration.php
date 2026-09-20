<?php
namespace Apie\TypescriptCodeBuilder\Dto;

use Apie\Core\Attributes\FakeMethod;
use Apie\TypescriptCodeBuilder\Dto\Typehints\ObjectTypeDefinition;
use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;
use Apie\TypescriptCodeBuilder\TypescriptTypeDeclarationInterface;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifier;
use Faker\Generator;

#[FakeMethod('createRandom')]
class TypescriptDeclaration implements TypescriptFileExpressionInterface
{
    public function __construct(
        public JavascriptIdentifier $name,
        public TypescriptTypeDeclarationInterface $typehint
    ) {
    }

    public static function createRandom(Generator $faker): self
    {
        return new self(JavascriptIdentifier::createRandom($faker), $faker->fakeClass(ObjectTypeDefinition::class));
    }

    public function toTypescript(): string
    {
        return 'type ' . $this->name . ' = ' . $this->typehint->toTypescript() . ';';
    }
    public function toJavascript(): string
    {
        return '';
    }
    public function providesDefinitions(): JavascriptIdentifierList
    {
        return new JavascriptIdentifierList([$this->name]);
    }
    public function needsDefinitions(): JavascriptIdentifierList
    {
        return $this->typehint->needsDefinitions();
    }
}
