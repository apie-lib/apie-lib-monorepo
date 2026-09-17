<?php
namespace Apie\TypescriptCodeBuilder\Dto;

use Apie\Core\Attributes\FakeMethod;
use Apie\Core\Attributes\Optional;
use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;
use Faker\Generator;

#[FakeMethod('createRandom')]
class RawJavascript implements TypescriptFileExpressionInterface
{
    #[Optional]
    public JavascriptIdentifierList $providesDefinition;

    #[Optional]
    public JavascriptIdentifierList $needsDefinition;

    public function __construct(
        public string $javascriptCode,
        public ?string $typescriptCode = null,
        array $providesDefinition = [],
        array $needsDefinition = [],
    ) {
        $this->providesDefinition = new JavascriptIdentifierList($providesDefinition);
        $this->needsDefinition = new JavascriptIdentifierList($needsDefinition);
    }

    public static function createRandom(Generator $faker): self
    {
        return new RawJavascript('// ' . $faker->text(40));
    }

    public function toTypescript(): string
    {
        return $this->typescriptCode ?? $this->javascriptCode;
    }
    public function toJavascript(): string
    {
        return $this->javascriptCode;
    }
    public function providesDefinitions(): JavascriptIdentifierList
    {
        return $this->providesDefinition;
    }
    public function needsDefinitions(): JavascriptIdentifierList
    {
        return $this->needsDefinition;
    }
}
