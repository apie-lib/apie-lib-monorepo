<?php
namespace Apie\HtmlBuilders\Components\Dashboard;

use Apie\HtmlBuilders\Components\BaseComponent;
use Apie\HtmlBuilders\Interfaces\ComponentInterface;
use Apie\HtmlBuilders\Lists\ComponentHashmap;

class RawContents extends BaseComponent
{
    public function __construct(private string $html)
    {
        parent::__construct(['html' => $html]);
    }
}