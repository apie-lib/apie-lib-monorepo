<?php
namespace Apie\CmsApiDropdownOption\RouteDefinitions;

use Apie\CmsApiDropdownOption\Actions\DropdownOptionsAction;
use Apie\Core\Enums\RequestMethod;
use Apie\Core\ValueObjects\UrlRouteDefinition;
use Apie\RestApi\RouteDefinitions\AbstractRestApiRouteDefinition;

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

    public function getAction(): string
    {
        return DropdownOptionsAction::class;
    }

    public function getOperationId(): string
    {
        return 'cms.dropdown_options.' . $this->class->getShortName();
    }
 }