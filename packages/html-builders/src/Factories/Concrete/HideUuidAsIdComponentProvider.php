<?php
namespace Apie\HtmlBuilders\Factories\Concrete;

use Apie\Core\Identifiers\Uuid;
use Apie\Core\Identifiers\UuidV4;
use Apie\HtmlBuilders\Components\Forms\HiddenField;
use Apie\HtmlBuilders\FormBuildContext;
use Apie\HtmlBuilders\Interfaces\ComponentInterface;
use Apie\HtmlBuilders\Interfaces\FormComponentProviderInterface;
use ReflectionNamedType;
use ReflectionType;

class HideUuidAsIdComponentProvider implements FormComponentProviderInterface
{
    public function supports(ReflectionType $type, FormBuildContext $context): bool
    {
        if (
            !$type instanceof ReflectionNamedType
            || $type->allowsNull()
            || $type->isBuiltIn()
            || !$context->getFormName()->hasChildFormFieldName()
            || $context->getFormName()->getChildFormFieldName() !== 'id'
        ) {
            return false;
        }
        return is_subclass_of($type->getName(), Uuid::class, true);
    }

    public function createComponentFor(ReflectionType $type, FormBuildContext $context): ComponentInterface
    {
        return new HiddenField(
            $context->getFormName(),
            $context->getFilledInValue(UuidV4::createRandom()->toNative())
        );
    }
}