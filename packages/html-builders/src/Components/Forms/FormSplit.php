<?php
namespace Apie\HtmlBuilders\Components\Forms;

use Apie\HtmlBuilders\Components\BaseComponent;
use Apie\HtmlBuilders\Interfaces\ComponentInterface;
use Apie\HtmlBuilders\Lists\ComponentHashmap;
use Apie\HtmlBuilders\ValueObjects\FormName;

class FormSplit extends BaseComponent
{
    public function __construct(FormName $name, mixed $value, ComponentHashmap $tabComponents)
    {
        $valuePerType = [];
        foreach ($tabComponents as $componentName => $component) {
            $valuePerType[$componentName] = $component->attributes['value'] ?? null;
        }
        $newTabsComponent = [];
        foreach ($tabComponents as $key => $component) {
            $md5 = 's' . md5((string) $name . ',' . $key);
            $newTabsComponent[$md5] = $this->makePrototype($md5, $component);
        }
        parent::__construct(
            [
                'name' => $name,
                'tmpl' => 's' . md5((string) $name),
                'tabs' => array_keys($newTabsComponent),
                'value' => $value,
                'valuePerType' => $valuePerType,
            ],
            new ComponentHashmap($newTabsComponent)
        );
    }

    public function withName(FormName $name, mixed $value = null): ComponentInterface
    {
        $item = clone $this;
        $item->attributes['name'] = $name;
        foreach ($item->childComponents as $key => $component) {
            $item->childComponents[$key] = $component->withName($name, $value);
        }
        return $item;
    }
}
