<?php
namespace Apie\CmsApiDropdownOption\RouteDefinitions;

use Apie\CmsApiDropdownOption\Actions\DropdownOptionsAction;
use Apie\Core\Enums\RequestMethod;
use Apie\Core\ValueObjects\UrlRouteDefinition;
use Apie\RestApi\RouteDefinitions\AbstractRestApiRouteDefinition;

 class DropdownOptionsForExistingObjectRouteDefinition extends AbstractRestApiRouteDefinition
 {
    public function getMethod(): RequestMethod
    {
        return RequestMethod::POST;
    }

    public function getUrl(): UrlRouteDefinition
    {
        return new UrlRouteDefinition($this->class->getShortName() . '/{id}/dropdown-options/{property}');
    }

    public function getAction(): string
    {
        return DropdownOptionsAction::class;
    }

    public function getOperationId(): string
    {
        return 'cms.dropdown_options.existing.' . $this->class->getShortName();
    }
 }