<?php
namespace Apie\Webdav\RouteDefinitions;

use Apie\Common\Interfaces\HasActionDefinition;
use Apie\Common\Interfaces\HasRouteDefinition;
use Apie\Common\Lists\UrlPrefixList;
use Apie\Core\Actions\ActionResponseStatusList;
use Apie\Core\Dto\ListOf;
use Apie\Core\Enums\RequestMethod;
use Apie\Core\Lists\StringList;
use Apie\Core\ValueObjects\UrlRouteDefinition;
use Apie\Webdav\Controller\WebdavController;
use ReflectionClass;
use ReflectionMethod;
use ReflectionType;

class WebdavRouteDefinition implements HasRouteDefinition, HasActionDefinition
{
    public function getMethod(): RequestMethod
    {
        return RequestMethod::ANY;
    }

    public function getOperationId(): string
    {
        return 'apie_webdav';
    }

    public function getUrl(): UrlRouteDefinition
    {
        return new UrlRouteDefinition('/webdav/{path}');
    }

    public function getController(): string
    {
        return WebdavController::class;
    }

    public function getAction(): string
    {
        return '__invoke';
    }

    public function getInputType(): ReflectionClass|ReflectionMethod|ReflectionType
    {
        return new ReflectionClass(StringList::class);
    }

    public function getOutputType(): ReflectionClass|ReflectionMethod|ReflectionType|ListOf
    {
        return new ReflectionClass(StringList::class);
    }

    public function getPossibleActionResponseStatuses(): ActionResponseStatusList
    {
        return new ActionResponseStatusList();
    }

    public function getDescription(): string
    {
        return 'Webdav';
    }

    public function getTags(): StringList
    {
        return new StringList(['webdav']);
    }

    public function getRouteAttributes(): array
    {
        return [
            'path' => '',
        ];
    }

    public function getUrlPrefixes(): UrlPrefixList
    {
        return new UrlPrefixList([]);
    }
}
