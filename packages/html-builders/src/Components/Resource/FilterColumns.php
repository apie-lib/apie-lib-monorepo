<?php
namespace Apie\HtmlBuilders\Components\Resource;

use Apie\Core\Lists\StringSet;
use Apie\HtmlBuilders\Components\BaseComponent;

class FilterColumns extends BaseComponent
{
    /**
     * @param array<string, string> $currentFilter
     */
    public function __construct(StringSet $filters, string $textSearch, array $currentFilter)
    {
        parent::__construct([
            'textSearch' => $textSearch,
            'currentFilter' => $currentFilter,
            'filters' => $filters,
        ]);
    }
}
