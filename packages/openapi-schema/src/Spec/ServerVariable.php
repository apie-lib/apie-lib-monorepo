<?php


namespace Apie\OpenapiSchema\Spec;


use Apie\ValueObjects\ValueObjectInterface;

/**
 * @TODO specification extension added
 */
class ServerVariable implements ValueObjectInterface
{
    private $defaultValue;
    private $description;
    private $enums = null;

    public function __construct(string $defaultValue, ?string $description, string... $enums)
    {
        $this->defaultValue = $defaultValue;
        $this->description = $description;
        if (!empty($enums)) {
            $this->enums = $enums;
            if (!in_array($defaultValue, $enums)) {
                throw new \LogicException('The default value "' . $defaultValue . '" is not in the enum!');
            }
        }

    }

    public function getDefault(): string
    {
        return $this->defaultValue;
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
        return $this->enums;
    }

    public static function fromNative($value)
    {
        return new self(
            $value['default'],
            $value['description'] ?? null,
            ...($value['enums'] ?? [])
        );
    }

    public function toNative()
    {
        $res = [];
        foreach (['default', 'description', 'enums'] as $field) {
            $fieldValue = $this->${'get' . $field}();
            if ($fieldValue !== null) {
                $res[$field] = $fieldValue;
            }
        }
        return $res;
    }
}