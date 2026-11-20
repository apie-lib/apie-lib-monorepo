<?php
namespace Apie\TypescriptCodeBuilder\Dto\Typehints;

use Apie\Core\Attributes\FakeMethod;
use Apie\Core\Attributes\Optional;
use Apie\TypescriptCodeBuilder\Enums\TypescriptType;
use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptTypeDeclarationInterface;
use Faker\Generator;

#[FakeMethod('createRandom')]
class RawTypeDefinition implements TypescriptTypeDeclarationInterface
{
    #[Optional]
    public JavascriptIdentifierList $providesDefinition;

    #[Optional]
    public JavascriptIdentifierList $needsDefinition;

    public function __construct(
        public string $typescriptCode,
        array $providesDefinition = [],
        array $needsDefinition = [],
    ) {
        $this->providesDefinition = new JavascriptIdentifierList($providesDefinition);
        $this->needsDefinition = new JavascriptIdentifierList($needsDefinition);
    }

    public static function createRandom(Generator $faker): self
    {
        return new RawTypeDefinition($faker->fakeClass(TypescriptType::class));
    }

    public function toTypescript(): string
    {
        return $this->typescriptCode;
    }
    public function toJavascript(): string
    {
        return '';
    }
    public function providesDefinitions(bool $applyBlockScope): JavascriptIdentifierList
    {
        return $this->providesDefinition;
    }
    public function needsDefinitions(): JavascriptIdentifierList
    {
        return $this->needsDefinition;
    }
}
