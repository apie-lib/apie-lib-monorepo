<?php


namespace Apie\OpenapiSchema\Spec;


use Apie\OpenapiSchema\ValueObjects\SecuritySchemeType;
use Apie\ValueObjects\ValueObjectInterface;

class SecurityRequirement implements ValueObjectInterface
{
    /**
     * @var string[][]
     */
    private $securityRequirementArray;

    public static function fromNative($value)
    {
        return new SecuritySchemeType($value);
    }

    public function toNative()
    {
        return $this->securityRequirementArray;
    }

    public function __construct(iterable $value)
    {
        $this->securityRequirementArray = [];
        foreach ($value as $name => $securityRequirements) {
            $list = [];
            foreach ($securityRequirements as $securityRequirement) {
                $list[] = $securityRequirement;
            }
            $this->securityRequirementArray[$name] = $list;
        }
    }
}