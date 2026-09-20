<?php
namespace Apie\TypescriptCodeBuilder\Dto\Typehints;

use Apie\Core\Attributes\FakeMethod;
use Apie\TypescriptCodeBuilder\Dto\FunctionArgument;
use Apie\TypescriptCodeBuilder\Enums\TypescriptType;
use Apie\TypescriptCodeBuilder\Lists\ArgumentList;
use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptTypeDeclarationInterface;
use Faker\Generator;

/**
 * Typescript definition for a callback type.
 */
#[FakeMethod('createRandom')]
class CallbackTypeDefinition implements TypescriptTypeDeclarationInterface
{
    public function __construct(
        public ArgumentList $arguments,
        public TypescriptTypeDeclarationInterface $returnType,
    ) {
    }

    public static function createRandom(Generator $faker)
    {
        return new self(
            $faker->fakeClass(ArgumentList::class),
            $faker->fakeClass(TypescriptType::class),
        );
    }

    public function toTypescript(): string
    {
        return '(' . $this->arguments->toTypescript() . ') => ' . $this->returnType->toTypescript();
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
        $definitions = $this->returnType->needsDefinitions();
        foreach ($this->arguments as $argument) {
            /** @var FunctionArgument $argument */
            foreach ($argument->needsDefinitions() as $definition) {
                $definitions = $definitions->append($definition);
            }
        }
        return $definitions;
    }
}
