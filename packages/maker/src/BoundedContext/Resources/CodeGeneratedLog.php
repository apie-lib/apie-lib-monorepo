<?php

namespace Apie\Maker\BoundedContext\Resources;

use Apie\Core\ApieLib;
use Apie\Core\Attributes\Context;
use Apie\Core\Attributes\FakeCount;
use Apie\Core\Attributes\FakeMethod;
use Apie\Core\Attributes\HideIdOnOverview;
use Apie\Core\Attributes\Not;
use Apie\Core\Attributes\Requires;
use Apie\Core\Attributes\RuntimeCheck;
use Apie\Core\BoundedContext\BoundedContext;
use Apie\Core\ContextConstants;
use Apie\Core\Datalayers\ApieDatalayer;
use Apie\Core\Entities\EntityInterface;
use Apie\Maker\BoundedContext\Identifiers\CodeGeneratedLogIdentifier;
use Apie\Maker\BoundedContext\Interfaces\CodeWriterConfigurationInterface;
use Apie\Maker\BoundedContext\Services\CodeWriter;
use Apie\Maker\Enums\OverwriteStrategy;
use Apie\Maker\Utils;
use DateTimeImmutable;
use ReflectionClass;
use Throwable;

#[FakeCount(0)]
#[HideIdOnOverview]
#[FakeMethod('createRandom')]
class CodeGeneratedLog implements EntityInterface, CodeWriterConfigurationInterface
{
    private CodeGeneratedLogIdentifier $id;

    private DateTimeImmutable $date;

    private ?string $errorMessage = null;

    private ?string $errorStacktrace = null;

    public function __construct(
        private OverwriteStrategy $strategy,
        #[Context()] ApieDatalayer $apieDatalayer,
        #[Context()] BoundedContext $boundedContext,
        #[Context()] CodeWriter $codeWriter,
        #[Context(Utils::MAKER_CONFIG)] private array $makerConfig
    ) {
        $this->id = CodeGeneratedLogIdentifier::createRandom();
        $this->date = ApieLib::getPsrClock()->now();
        try {
            $codeWriter->startWriting($this);
            foreach ($apieDatalayer->all(new ReflectionClass(ResourceDefinition::class), $boundedContext) as $resourceDefinition) {
                $boundedContextDefinition = $apieDatalayer->find($resourceDefinition->boundedContextId, $boundedContext);
                $codeWriter->writeResource($this, $resourceDefinition, $boundedContextDefinition);
                $codeWriter->writeIdentifier($this, $resourceDefinition, $boundedContextDefinition);
            }
        } catch (Throwable $error) {
            $this->errorMessage = $error->getMessage();
            $this->errorStacktrace = $error->getTraceAsString();
        }
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

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    #[RuntimeCheck(new Not(new Requires(ContextConstants::GET_ALL_OBJECTS)))]
    public function getErrorStacktrace(): ?string
    {
        return $this->errorStacktrace;
    }

    #[RuntimeCheck(new Not(new Requires(ContextConstants::GET_ALL_OBJECTS)))]
    public function getTargetPath(): string|false|null
    {
        return $this->makerConfig['target_path'] ?? null;
    }

    #[RuntimeCheck(new Not(new Requires(ContextConstants::GET_ALL_OBJECTS)))]
    public function getTargetNamespace(string $sub = ''): ?string
    {
        if (!isset($this->makerConfig['target_namespace'])) {
            return null;
        }
        if ($sub === '') {
            return rtrim($this->makerConfig['target_namespace'], '\\');
        }
        return rtrim($this->makerConfig['target_namespace'], '\\') . '\\' . ltrim($sub, '\\');
    }

    public static function createRandom(): never
    {
        throw new \RuntimeException('I can not create a random instance of CodeGeneratedLog');
    }
}
