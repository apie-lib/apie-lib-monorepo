<?php
namespace Apie\IanaValueObjects;

trait IsLanguageSubtag
{
    use StaticDataValueObject;

    public function toPreferredValue(): static
    {
        $preferred = $this->getFieldValue('Preferred-Value');
        if ($preferred === null) {
            return $this;
        }
        return new static($preferred);
    }

    protected function convert(string $input): string
    {
        $inputLower = strtolower($input);
        $data = static::getData();
        if (!isset($data[$inputLower])) {
            return $input;
        }

        return $data[$inputLower]['Subtag'] ?? $input;
    }
}
