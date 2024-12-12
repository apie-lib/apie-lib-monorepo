<?php
namespace Apie\IntegrationTests\Console;

use Apie\Core\Context\ApieContext;
use Apie\Core\Dto\DtoInterface;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\Metadata\MetadataFactory;
use ReflectionClass;

class InteractiveConsoleCommand implements DtoInterface {
    /**
     * @param class-string<EntityInterface> $class
     * @param array<string, array<int, int|float|string>> $inputPerField
     */
    public function __construct(
        public readonly string $command,
        public readonly string $class,
        public readonly array $inputPerField
    ) {

    }

    /**
     * @return array<int, string|int|float>
     */
    public function getInputs(): array
    {
        $metadata = MetadataFactory::getCreationMetadata(new ReflectionClass($this->class), new ApieContext());
        $inputs = [];
        foreach ($metadata->getHashmap() as $key => $mapping) {
            $inputs = [...$inputs, ...$this->inputPerField[$key]];
        }
        return $inputs;
    }
}