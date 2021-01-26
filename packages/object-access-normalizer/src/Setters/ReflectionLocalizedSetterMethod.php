<?php

namespace Apie\ObjectAccessNormalizer\Setters;

use ReflectionMethod;
use Symfony\Component\PropertyInfo\Type;
use Apie\ObjectAccessNormalizer\Interfaces\LocalizationAwareInterface;
use Apie\ObjectAccessNormalizer\TypeUtils;

final class ReflectionLocalizedSetterMethod implements SetterInterface
{
    /**
     * @var ReflectionMethod
     */
    private $method;

    /**
     * @var callable
     */
    private $conversionFn;

    /**
     * @var LocalizationAwareInterface
     */
    private $localizationAware;

    public function __construct(ReflectionMethod $method, LocalizationAwareInterface $localizationAware, callable $conversionFn)
    {
        $this->method = $method;
        $this->localizationAware = $localizationAware;
        $this->conversionFn = $conversionFn;
    }

    public function getName(): string
    {
        return $this->method->getName();
    }

    public function setValue($object, $value)
    {
        return $this->method->invoke($object, call_user_func($this->conversionFn, $this->localizationAware->getContentLanguage()), $value);
    }

    public function getPriority(): int
    {
        return 4;
    }

    public function toType(): ?Type
    {
        return TypeUtils::convertMethodToType($this->method);
    }
}
