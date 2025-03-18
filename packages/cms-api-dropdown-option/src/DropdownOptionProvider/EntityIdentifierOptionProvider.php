<?php
namespace Apie\CmsApiDropdownOption\DropdownOptionProvider;

use Apie\CmsApiDropdownOption\Dtos\DropdownOption;
use Apie\CmsApiDropdownOption\Lists\DropdownOptionList;
use Apie\Core\BoundedContext\BoundedContext;
use Apie\Core\BoundedContext\BoundedContextHashmap;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Context\ApieContext;
use Apie\Core\ContextConstants;
use Apie\Core\Datalayers\ApieDatalayer;
use Apie\Core\Datalayers\Search\QuerySearch;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\Exceptions\InvalidTypeException;
use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Core\Metadata\Fields\FieldInterface;
use Apie\Core\ValueObjects\Utils;
use ReflectionClass;
use ReflectionNamedType;

final class EntityIdentifierOptionProvider extends BaseDropdownOptionProvider
{
    public function __construct(
        private readonly BoundedContextHashmap $boundedContextHashmap,
        private readonly ApieDatalayer $apieDatalayer
    ) {
    }

    protected function supportsField(FieldInterface $fieldMetadata, ApieContext $apieContext): bool
    {
        return null !== $this->getBoundedContext($fieldMetadata, $apieContext);
    }

    private function getBoundedContext(FieldInterface $fieldMetadata, ApieContext $apieContext): ?BoundedContext
    {
        $typehint = $fieldMetadata->getTypehint();
        if (!$typehint instanceof ReflectionNamedType || $typehint->isBuiltin()) {
            return null;
        }
        $class = new ReflectionClass($typehint->getName());
        if (!in_array(IdentifierInterface::class, $class->getInterfaceNames())) {
            return null;
        }
        $boundedContextId = $apieContext->hasContext(ContextConstants::BOUNDED_CONTEXT_ID)
            ? new BoundedContextId($apieContext->getContext(ContextConstants::BOUNDED_CONTEXT_ID))
            : null;
        return $this->boundedContextHashmap->getBoundedContextFromClassName($class, $boundedContextId);
    }

    protected function createDropdownList(
        string $property,
        FieldInterface $fieldMetadata,
        string $searchTerm,
        ApieContext $apieContext
    ): DropdownOptionList {
        $boundedContext = $this->getBoundedContext($fieldMetadata, $apieContext);
        assert($boundedContext instanceof BoundedContext);
        $typehint = $fieldMetadata->getTypehint();
        assert($typehint instanceof ReflectionNamedType);
        $class = new ReflectionClass($typehint->getName());
        if (in_array(IdentifierInterface::class, $class->getInterfaceNames())) {
            $class = $class->getMethod('getReferenceFor')->invoke(null);
        }
        $result = $this->apieDatalayer->all($class, $boundedContext->getId())
            ->toPaginatedResult(new QuerySearch(0, textSearch: $searchTerm, apieContext: $apieContext));
        $list = [];
        foreach ($result as $entity) {
            $list[] = new DropdownOption($entity->getId(), $this->determineDisplayValue($entity));
        }

        return new DropdownOptionList($list);
    }

    private function determineDisplayValue(EntityInterface $entity): string
    {
        $methods = ['getName', 'getDescription', '__toString', 'getId'];
        foreach ($methods as $method) {
            if (is_callable([$entity, $method])) {
                return Utils::toString($entity->$method());
            }
        }

        throw new InvalidTypeException($entity, 'EntityInterface');
    }
}
