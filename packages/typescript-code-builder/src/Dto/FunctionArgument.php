<?php
namespace Apie\TypescriptCodeBuilder\Dto;

use Apie\Core\Attributes\FakeMethod;
use Apie\TypescriptCodeBuilder\Enums\TypescriptType;
use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;
use Apie\TypescriptCodeBuilder\TypescriptTypeDeclarationInterface;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifier;
use Faker\Generator;

#[FakeMethod('createRandom')]
class FunctionArgument implements TypescriptFileExpressionInterface
{
    public function __construct(
        public JavascriptIdentifier $name,
        public ?TypescriptTypeDeclarationInterface $typehint = null,
        public bool $optional = false,
    ) {
    }

    public static function createRandom(Generator $faker): self
    {
        return new self(
            $faker->fakeClass(JavascriptIdentifier::class),
            $faker->boolean(95) ? $faker->fakeClass(TypescriptType::class) : null,
            $faker->boolean(5)
        );
    }

    public function toTypescript(): string
    {
        if ($this->typehint === null) {
            return $this->name->toNative() . ($this->optional ? '?: unknown' : '');
        }
        return $this->name->toNative() . ($this->optional ? '?: ' : ': ') . $this->typehint->toTypescript();
    }
    public function toJavascript(): string
    {
        return $this->name->toNative();
    }
    public function providesDefinitions(bool $applyBlockScope): JavascriptIdentifierList
    {
        return new JavascriptIdentifierList();
    }
    public function needsDefinitions(): JavascriptIdentifierList
    {
        return $this->typehint ? $this->typehint->needsDefinitions() : new JavascriptIdentifierList();
    }
}
