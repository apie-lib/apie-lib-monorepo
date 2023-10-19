<?php
namespace Apie\IntegrationTests;

use Apie\CountWords\WordCounter;
use Stringable;

final class SearchTerm implements Stringable
{
    public function __construct(private readonly string $searchTerm)
    {
    }

    public function __toString(): string
    {
        return $this->searchTerm;
    }

    public function hasSearchTerms(): bool
    {
        $words = WordCounter::countFromString($this->searchTerm);

        return count($words) > 0;
    }
}