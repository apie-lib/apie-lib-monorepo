<?php
namespace Apie\HtmlBuilders\Components\Forms;

use Apie\Core\Attributes\CmsSingleInput;
use Apie\Core\Translator\Lists\TranslationStringSet;
use Apie\Core\Translator\ValueObjects\TranslationString;
use Apie\HtmlBuilders\Components\BaseComponent;
use ReflectionType;

class SingleInput extends BaseComponent
{
    public function __construct(
        string $name,
        mixed $value,
        TranslationStringSet $label,
        bool $nullable = false,
        ReflectionType $type,
        CmsSingleInput $input
    ) {   
        parent::__construct(
            [
                'name' => $name,
                'value' => $value,
                'label' => $label,
                'nullable' => $nullable,
                'types' => $input->types,
                'options' => $input->options->forType($type),
            ]
        );
    }
}
