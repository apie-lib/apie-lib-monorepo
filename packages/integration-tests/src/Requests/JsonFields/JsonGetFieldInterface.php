<?php

namespace Apie\IntegrationTests\Requests\JsonFields;

interface JsonGetFieldInterface
{
    public function getName(): string;
    public function getInputValue(): mixed;
}
