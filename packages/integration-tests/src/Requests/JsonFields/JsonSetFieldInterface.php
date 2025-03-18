<?php

namespace Apie\IntegrationTests\Requests\JsonFields;

interface JsonSetFieldInterface
{
    public function getName(): string;

    public function assertResponseValue(mixed $responseValue): void;
}
