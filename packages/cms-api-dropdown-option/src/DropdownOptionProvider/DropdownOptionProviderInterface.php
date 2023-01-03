<?php
namespace Apie\CmsApiDropdownOption\DropdownOptionProvider;

use Apie\CmsApiDropdownOption\Lists\DropdownOptionList;
use Apie\Core\Context\ApieContext;

interface DropdownOptionProviderInterface
{
    public function supports(ApieContext $apieContext): bool;
    public function getList(ApieContext $apieContext, string $searchTerm): DropdownOptionList;
}
