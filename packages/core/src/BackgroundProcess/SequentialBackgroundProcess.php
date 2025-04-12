<?php
namespace Apie\Core\BackgroundProcess;

use Apie\Core\ApieLib;
use Apie\Core\Attributes\AlwaysDisabled;
use Apie\Core\Attributes\Context;
use Apie\Core\Attributes\FakeCount;
use Apie\Core\Attributes\StaticCheck;
use Apie\Core\Context\ApieContext;
use Apie\Core\ContextConstants;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\Identifiers\PascalCaseSlug;
use Apie\Core\Identifiers\Ulid;
use Apie\Core\Lists\ItemHashmap;
use Apie\Core\Lists\ItemList;
use Apie\Core\ValueObjects\DatabaseText;
use DateTimeInterface;
use ReflectionClass;
use Throwable;

#[FakeCount(0)]
class SequentialBackgroundProcess implements EntityInterface
{
    private int $version;
    private int $step;
    private int $retries = 0;
    private DateTimeInterface $startTime;
    private ?DateTimeInterface $completionTime = null;
    private DatabaseText $className;
    private BackgroundProcessStatus $status = BackgroundProcessStatus::Active;
    private SequentialBackgroundProcessIdentifier $id;
    private mixed $result = null;

    #[StaticCheck(new AlwaysDisabled())]
    public function __construct(
        BackgroundProcessDeclaration $backgroundProcessDeclaration,
        private ItemHashmap|ItemList $payload
    ) {
        $this->className = new DatabaseText(get_debug_type($backgroundProcessDeclaration));
        $this->version = $backgroundProcessDeclaration->getCurrentVersion();
        $this->step = 0;
        $this->startTime = ApieLib::getPsrClock()->now();
        $this->id = new SequentialBackgroundProcessIdentifier(
            new PascalCaseSlug((new ReflectionClass($backgroundProcessDeclaration))->getShortName()),
            Ulid::createRandom()
        );
    }

    public function getId(): SequentialBackgroundProcessIdentifier
    {
        return $this->id;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function getStep(): int
    {
        return $this->step;
    }

    public function getRetries(): int
    {
        return $this->retries;
    }

    public function getStartTime(): DateTimeInterface
    {
        return $this->startTime;
    }

    public function getCompletionTime(): ?DateTimeInterface
    {
        return $this->completionTime;
    }

    public function getStatus(): BackgroundProcessStatus
    {
        return $this->status;
    }

    public function getResult(): mixed
    {
        return $this->result;
    }

    public function runStep(#[Context()] ApieContext $apieContext)
    {
        if ($this->status !== BackgroundProcessStatus::Active) {
            throw new \LogicException('Process ' . $this->id . ' can not be executed!');
        }
        $apieContext = $apieContext->withContext(ContextConstants::BACKGROUND_PROCESS, 1);
        $maxRetries = 1;
        try {
            $maxRetries = $this->className::getMaxRetries($this->version);
            $steps = array_values($this->className::retrieveDeclaration($this->version));
            if (isset($steps[$this->step])) {
                $this->result = call_user_func($steps[$this->step], $apieContext, $this->payload);
                $this->step++;
                $this->retries = 0;
            } else {
                $this->completionTime = ApieLib::getPsrClock()->now();
                $this->status = BackgroundProcessStatus::Finished;
            }
        } catch (Throwable $error) {
            if ($this->retries >= $maxRetries) {
                $this->status = BackgroundProcessStatus::TooManyErrors;
            }
        }
    }
}
