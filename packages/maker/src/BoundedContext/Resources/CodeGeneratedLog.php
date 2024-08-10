<?php

namespace Apie\Maker\BoundedContext\Resources;

use Apie\Core\ApieLib;
use Apie\Core\Attributes\Context;
use Apie\Core\Attributes\FakeCount;
use Apie\Core\Datalayers\ApieDatalayer;
use Apie\Maker\BoundedContext\Identifiers\CodeGeneratedLogIdentifier;
use Apie\Maker\Enums\OverwriteStrategy;
use Apie\Maker\Utils;
use DateTimeImmutable;

#[FakeCount(0)]
class CodeGeneratedLog implements \Apie\Core\Entities\EntityInterface
{
    private CodeGeneratedLogIdentifier $id;

    private DateTimeImmutable $date;

    public function __construct(
        private OverwriteStrategy $strategy,
        #[Context()] ApieDatalayer $apieDatalayer,
        #[Context(Utils::MAKER_CONFIG)] private array $makerConfig
    ) {
        $this->id = CodeGeneratedLogIdentifier::createRandom();
        $this->date = ApieLib::getPsrClock()->now();
    }

    public function getId(): CodeGeneratedLogIdentifier
    {
        return $this->id;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getOverwriteStrategy(): OverwriteStrategy
    {
        return $this->strategy;
    }
}
