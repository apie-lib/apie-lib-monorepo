<?php
namespace Apie\TypescriptCodeBuilder\Dto;

use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;

class ImportStatement implements TypescriptFileExpressionInterface
{
    public function __construct(
        public string $source,
        public JavascriptIdentifierList $imports,
        public bool $typeOnly = false,
    ) {
    }

    public function toTypescript(): string
    {
        $imports = implode(', ', array_map(
            static fn (\Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifier $import): string => $import->toNative(),
            $this->imports->toArray(),
        ));
        $type = $this->typeOnly ? ' type' : '';
        $source = json_encode($this->source, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES);
        return 'import' . $type . ' { ' . $imports . ' } from ' . $source . ';';
    }

    public function toJavascript(): string
    {
        return $this->typeOnly ? '' : $this->toTypescript();
    }

    public function providesDefinitions(): JavascriptIdentifierList
    {
        return $this->imports;
    }

    public function needsDefinitions(): JavascriptIdentifierList
    {
        return new JavascriptIdentifierList();
    }
}