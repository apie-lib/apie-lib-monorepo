<?php
namespace Apie\HtmlBuilders\Components\Forms;

use Apie\HtmlBuilders\Components\BaseComponent;
use Apie\HtmlBuilders\ValueObjects\FormName;

class HiddenField extends BaseComponent
{
    public function __construct(FormName $name, string $value)
    {
        parent::__construct(
            [
                'name' => $name,
                'value' => $value
            ]
        );
    }
}
