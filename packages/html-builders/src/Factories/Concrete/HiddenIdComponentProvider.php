<?php
namespace Apie\HtmlBuilders\Factories\Concrete;

use Apie\Core\Enums\ScalarType;
use Apie\Core\Metadata\MetadataFactory;
use Apie\HtmlBuilders\Components\Dashboard\RawContents;
use Apie\HtmlBuilders\FormBuildContext;
use Apie\HtmlBuilders\Interfaces\ComponentInterface;
use Apie\HtmlBuilders\Interfaces\FormComponentProviderInterface;
use ReflectionType;

class HiddenIdComponentProvider implements FormComponentProviderInterface
{
    public function supports(ReflectionType $type, FormBuildContext $context): bool
    {
        if (!$type->allowsNull() || $context->getFormName()->getChildFormFieldName() !== 'id') {
            return false;
        }
        $metadata = MetadataFactory::getCreationMetadata($type, $context->getApieContext());
        return $metadata->toScalarType() === ScalarType::STRING;
    }

    public function createComponentFor(ReflectionType $type, FormBuildContext $context): ComponentInterface
    {
        return new RawContents('<!-- id is filled in automatically -->');
    }
}