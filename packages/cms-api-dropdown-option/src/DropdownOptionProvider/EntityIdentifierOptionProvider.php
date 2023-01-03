<?php
namespace Apie\CmsApiDropdownOption\DropdownOptionProvider;

use Apie\CmsApiDropdownOption\Dtos\DropdownOption;
use Apie\CmsApiDropdownOption\Lists\DropdownOptionList;
use Apie\Common\ContextConstants;
use Apie\CompositeValueObjects\Fields\FieldInterface;
use Apie\Core\BoundedContext\BoundedContext;
use Apie\Core\BoundedContext\BoundedContextHashmap;
use Apie\Core\Context\ApieContext;
use Apie\Core\Datalayers\ApieDatalayer;
use Apie\Core\Datalayers\Search\QuerySearch;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\Exceptions\InvalidTypeException;
use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Core\Metadata\MetadataInterface;
use Apie\Core\ValueObjects\Utils;
use ReflectionClass;

final class EntityIdentifierOptionProvider extends BaseDropdownOptionProvider
{
    public function __construct(
        private readonly BoundedContextHashmap $boundedContextHashmap,
        private readonly ApieDatalayer $apieDatalayer
    ) {
    }

    protected function supportsClass(ReflectionClass $class, ApieContext $apieContext): bool
    {
        return $class->implementsInterface(IdentifierInterface::class)
            && null !== $this->getBoundedContext($class, $apieContext);
    }

    private function getBoundedContext(ReflectionClass $class, ApieContext $apieContext): ?BoundedContext
    {
        $boundedContextId = $apieContext->hasContext(ContextConstants::BOUNDED_CONTEXT_ID)
            ? $apieContext->getContext(ContextConstants::BOUNDED_CONTEXT_ID)
            : null;
        return $this->boundedContextHashmap->getBoundedContextFromClassName($class, $boundedContextId);
    }

    protected function createDropdownList(
        ReflectionClass $class,
        MetadataInterface $metadata,
        string $property,
        FieldInterface $fieldMetadata,
        string $searchTerm,
        ApieContext $apieContext
    ): DropdownOptionList {
        $boundedContext = $this->getBoundedContext($class, $apieContext);
        assert($boundedContext instanceof BoundedContext);
        $result = $this->apieDatalayer->all($class)->toPaginatedResult(new QuerySearch(0, 20, $searchTerm));
        $list = [];
        foreach ($result as $entity) {
            $list[] = new DropdownOption($entity->getId(), $this->determineDisplayValue($entity));
        }

        return new DropdownOptionList($list);
    }

    private function determineDisplayValue(EntityInterface $entity): string
    {
        $methods = ['__toString', 'getName', 'getDescription', 'getId'];
        foreach ($methods as $method) {
            if (is_callable([$entity, $method])) {
                return Utils::toString($entity->$method());
            }
        }

        throw new InvalidTypeException($entity, 'EntityInterface');
    }
}
