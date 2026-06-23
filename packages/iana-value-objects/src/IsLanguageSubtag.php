<?php
namespace Apie\IanaValueObjects;

trait IsLanguageSubtag
{
    use StaticDataValueObject, IsSanitizingInput {
        IsSanitizingInput::convert insteadof StaticDataValueObject;
    }

    public function toPreferredValue(): static
    {
        $preferred = $this->getFieldValue('Preferred-Value');
        if ($preferred === null) {
            return $this;
        }
        return new static($preferred);
    }
}
