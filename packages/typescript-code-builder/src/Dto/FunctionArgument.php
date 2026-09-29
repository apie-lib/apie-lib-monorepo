<?php
namespace Apie\TypescriptCodeBuilder\Dto;

use Apie\Core\Attributes\FakeMethod;
use Apie\TypescriptCodeBuilder\Enums\TypescriptType;
use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;
use Apie\TypescriptCodeBuilder\TypescriptTypeDeclarationInterface;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifierKey;
use Faker\Generator;

#[FakeMethod('createRandom')]
class FunctionArgument implements TypescriptFileExpressionInterface
{
    public function __construct(
        public JavascriptIdentifierKey $name,
        public ?TypescriptTypeDeclarationInterface $typehint = null,
        public bool $optional = false,
    ) {
    }

    public static function createRandom(Generator $faker): self
    {
        return new self(
            $faker->fakeClass(JavascriptIdentifierKey::class),
            $faker->boolean(95) ? $faker->fakeClass(TypescriptType::class) : null,
            $faker->boolean(5)
        );
    }

    public function toTypescript(): string
    {
        if ($this->typehint === null) {
            return $this->name->toCode() . ($this->optional ? '?: unknown' : ': any');
        }
        return $this->name->toCode() . ($this->optional ? '?: ' : ': ') . $this->typehint->toTypescript();
    }
    public function toJavascript(): string
    {
        return $this->name->toCode();
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
