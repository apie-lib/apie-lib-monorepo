<?php
namespace Apie\TypescriptCodeBuilder\Dto\Expressions;

use Apie\Core\Attributes\FakeMethod;
use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifier;
use Faker\Generator;

#[FakeMethod('createRandom')]
class PropertyAccessExpression implements TypescriptFileExpressionInterface
{
    public function __construct(
        public TypescriptFileExpressionInterface $object,
        public JavascriptIdentifier $property
    ) {
    }

    public function toTypescript(): string
    {
        return $this->object->toTypescript() . '.' . $this->property->toNative();
    }
    public function toJavascript(): string
    {
        return $this->object->toJavascript() . '.' . $this->property->toNative();
    }
    public function providesDefinitions(): JavascriptIdentifierList
    {
        return new JavascriptIdentifierList();
    }
    public function needsDefinitions(): JavascriptIdentifierList
    {
        return $this->object->needsDefinitions();
    }

    public static function createRandom(Generator $faker): self
    {
        return new self(
            $faker->fakeClass(IdentifierExpression::class),
            JavascriptIdentifier::createRandom($faker)
        );
    }
}
