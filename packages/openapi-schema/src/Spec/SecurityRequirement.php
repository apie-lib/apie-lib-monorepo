<?php


namespace Apie\OpenapiSchema\Spec;

use Apie\ValueObjects\ValueObjectInterface;

/**
 * @see https://swagger.io/specification/#security-requirement-object
 */
class SecurityRequirement implements ValueObjectInterface
{
    /**
     * @var string[][]
     */
    private $securityRequirementArray;

    public static function fromNative($value)
    {
        return new SecurityRequirement($value);
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
                $list[] = (string) $securityRequirement;
            }
            $this->securityRequirementArray[$name] = $list;
        }
    }
}
