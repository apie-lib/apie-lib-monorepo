<?php
namespace Apie\CmsApiDropdownOption\RouteDefinitions;

use Apie\CmsApiDropdownOption\Actions\DropdownOptionsAction;
use Apie\CmsApiDropdownOption\Controllers\DropdownOptionController;
use Apie\Common\Enums\UrlPrefix;
use Apie\Common\Lists\UrlPrefixList;
use Apie\Common\RouteDefinitions\AbstractRestApiRouteDefinition;
use Apie\Core\Enums\RequestMethod;
use Apie\Core\ValueObjects\UrlRouteDefinition;

class DropdownOptionsForNewObjectRouteDefinition extends AbstractRestApiRouteDefinition
{
    public function getMethod(): RequestMethod
    {
        return RequestMethod::POST;
    }

    public function getUrl(): UrlRouteDefinition
    {
        return new UrlRouteDefinition($this->class->getShortName() . '/dropdown-options/{property}');
    }

    public function getController(): string
    {
        return DropdownOptionController::class;
    }

    public function getAction(): string
    {
        return DropdownOptionsAction::class;
    }

    public function getOperationId(): string
    {
        return 'cms.dropdown_options.' . $this->class->getShortName();
    }

    final public function getUrlPrefixes(): UrlPrefixList
    {
        return new UrlPrefixList([UrlPrefix::CMS, UrlPrefix::API]);
    }
}
