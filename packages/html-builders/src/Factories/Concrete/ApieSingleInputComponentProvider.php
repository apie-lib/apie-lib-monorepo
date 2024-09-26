<?php
namespace Apie\HtmlBuilders\Factories\Concrete;

use Apie\Core\Attributes\CmsSingleInput;
use Apie\Core\Utils\ConverterUtils;
use Apie\HtmlBuilders\Components\Forms\SingleInput;
use Apie\HtmlBuilders\FormBuildContext;
use Apie\HtmlBuilders\Interfaces\ComponentInterface;
use Apie\HtmlBuilders\Interfaces\FormComponentProviderInterface;
use ReflectionClass;
use ReflectionType;

class ApieSingleInputComponentProvider implements FormComponentProviderInterface
{
    /** @var array<class-string<object>, CmsSingleInput|null> $alreadyChecked */
    private static array $alreadyChecked = [];

    private function getSingleInputAttribute(ReflectionClass $class): ?CmsSingleInput
    {
        if (!isset(self::$alreadyChecked[$class->name])) {
            self::$alreadyChecked[$class->name] = null;
            foreach ($class->getAttributes(CmsSingleInput::class) as $input) {
                return self::$alreadyChecked[$class->name] = $input->newInstance();
            }
            foreach ($class->getInterfaceNames() as $interfaceName) {
                self::$alreadyChecked[$class->name] ??= $this->getSingleInputAttribute(new ReflectionClass($interfaceName));
            }
            foreach ($class->getTraitNames() as $traitName) {
                self::$alreadyChecked[$class->name] ??= $this->getSingleInputAttribute(new ReflectionClass($traitName));
            }
        }
        return self::$alreadyChecked[$class->name];
    }

    public function supports(ReflectionType $type, FormBuildContext $context): bool
    {
        $class = ConverterUtils::toReflectionClass($type);
        if ($class === null) {
            return false;
        }
        return (bool) $this->getSingleInputAttribute($class);
    }
    public function createComponentFor(ReflectionType $type, FormBuildContext $context): ComponentInterface
    {
        $class = ConverterUtils::toReflectionClass($type);
        return new SingleInput(
            $context->getFormName(),
            $context->getFilledInValue(),
            $context->createTranslationLabel(),
            $type->allowsNull(),
            $type,
            $this->getSingleInputAttribute($class) ?? new CmsSingleInput([])
        );
    }
}