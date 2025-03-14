<?php
namespace Apie\IntegrationTests\Console;

use Apie\Core\Context\ApieContext;
use Apie\Core\Dto\DtoInterface;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\Metadata\Fields\DiscriminatorColumn;
use Apie\Core\Metadata\MetadataFactory;
use ReflectionClass;

class InteractiveConsoleCommand implements DtoInterface
{
    /**
     * @param class-string<EntityInterface> $class
     * @param array<string, array<int, int|float|string>> $inputPerField
     */
    public function __construct(
        public readonly string $command,
        public readonly string $class,
        public readonly array $inputPerField,
        public readonly ?string $polymorphicClass = null
    ) {

    }

    /**
     * @return array<int, string|int|float>
     */
    public function getInputs(): array
    {
        $metadata = MetadataFactory::getCreationMetadata(new ReflectionClass($this->polymorphicClass ?? $this->class), new ApieContext());
        $inputs = [];
        if ($this->polymorphicClass) {
            $inputs[] = $this->polymorphicClass;
        }
        $handled = [];
        foreach ($metadata->getHashmap() as $key => $mapping) {
            if ($mapping instanceof DiscriminatorColumn) {
                continue;
            }
            $handled[] = $key;
            $inputs = [...$inputs, ...$this->inputPerField[$key]];
        }
        $diff = array_diff($handled, array_keys($this->inputPerField));
        if (!empty($diff)) {
            throw new \LogicException('Mapping inputs failed, found unknown keys: ' . implode(', ', $diff));
        }
        return $inputs;
    }
}
