<?php
namespace Apie\CmsApiDropdownOption\DropdownOptionProvider;

use Apie\CmsApiDropdownOption\Lists\DropdownOptionList;
use Apie\Core\Context\ApieContext;

final class ChainedDropdownOptionProvider implements DropdownOptionProviderInterface
{
    /**
     * @var array<int, DropdownOptionProviderInterface> $providers
     */
    private array $providers;

    public function __construct(DropdownOptionProviderInterface... $providers)
    {
        $this->providers = $providers;
    }

    /**
     * @param iterable<int, DropdownOptionProviderInterface> $providers
     */
    public static function create(iterable $providers): self
    {
        return new self(...$providers);
    }

    public function supports(ApieContext $apieContext): bool
    {
        foreach ($this->providers as $provider) {
            if ($provider->supports($apieContext)) {
                return true;
            }
        }

        return false;
    }

    public function getList(ApieContext $apieContext, string $searchTerm): DropdownOptionList
    {
        foreach ($this->providers as $provider) {
            if ($provider->supports($apieContext)) {
                return $provider->getList($apieContext, $searchTerm);
            }
        }

        return new DropdownOptionList();
    }
}
