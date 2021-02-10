<?php


namespace Apie\OpenapiSchema\Spec;

use Apie\CompositeValueObjects\ValueObjects\StringList;
use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\OpenapiSchema\Exceptions\DefaultValueNotInEnum;
use Apie\OpenapiSchema\ValueObjects\SpecificationExtension;
use Apie\ValueObjects\ValueObjectInterface;

/**
 * @see https://swagger.io/specification/#server-variable-object
 */
class ServerVariable implements ValueObjectInterface
{
    use CompositeValueObjectWithExtension;

    /**
     * @var string
     */
    private $default;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var StringList|null
     */
    private $enums = null;

    public function __construct(string $default, ?string $description, string... $enums)
    {
        $this->default = $default;
        $this->description = $description;
        if (!empty($enums)) {
            $this->enums = StringList::fromNative($enums);
        }
        $this->validateProperties();
        $this->specificationExtension = new SpecificationExtension([]);
    }

    protected function validateProperties(): void
    {
        if (!empty($this->enums) && !in_array($this->default, $this->enums->toNative(), true)) {
            throw new DefaultValueNotInEnum($this->default, ...$this->enums);
        }
    }

    public function getDefault(): string
    {
        return $this->default;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return string[]|null
     */
    public function getEnums(): ?array
    {
        return $this->enums ? $this->enums->toNative() : null;
    }
}
