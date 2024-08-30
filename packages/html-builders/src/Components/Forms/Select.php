<?php
namespace Apie\HtmlBuilders\Components\Forms;

use Apie\Core\Lists\ValueOptionList;
use Apie\HtmlBuilders\Components\BaseComponent;
use Apie\HtmlBuilders\ValueObjects\FormName;

class Select extends BaseComponent
{
    public function __construct(FormName $name, string $value, ValueOptionList $choiceList)
    {
        parent::__construct(
            [
                'name' => $name,
                'value' => $value,
                'options' => $choiceList
            ]
        );
    }
}
