<?php


namespace Apie\OpenapiSchema\Contract;

use Apie\OpenapiSchema\Spec\ServerVariableObjectList;

interface ServerContract extends \JsonSerializable
{
    public function getUrl(): string;

    public function getDescription(): ?string;

    public function getVariables(): ServerVariableObjectList;

    public function jsonSerialize(): array;
}
