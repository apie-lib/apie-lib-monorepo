<?php
namespace Apie\Core\Repositories\Search;

use Apie\Core\Lists\StringHashmap;

final class QuerySearch
{
    private ?string $textSearch;

    private StringHashmap $searches;

    public function __construct(?string $textSearch = null, ?StringHashmap $searches = null)
    {
        $this->textSearch = $textSearch;
        $this->searches = null === $searches ? new StringHashmap() : $searches;
    }

    public function getTextSearch(): ?string
    {
        return $this->textSearch;
    }

    public function getSearches(): StringHashmap
    {
        return $this->searches;
    }
}
