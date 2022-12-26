<?php
namespace Apie\CmsApiDropdownOption\Actions;

use Apie\CmsApiDropdownOption\Dtos\DropdownOption;
use Apie\Common\ApieFacade;
use Apie\Common\ContextConstants;
use Apie\Core\Actions\ActionInterface;
use Apie\Core\Actions\ActionResponse;
use Apie\Core\Actions\ActionResponseStatus;
use Apie\Core\Actions\ActionResponseStatusList;
use Apie\Core\Actions\ApieFacadeInterface;
use Apie\Core\Context\ApieContext;
use Apie\Core\Dto\ListOf;
use Apie\Core\Lists\StringList;
use Apie\Core\ReflectionTypeFactory;
use ReflectionClass;
use ReflectionType;

class DropdownOptionsAction implements ActionInterface
{
    public function __construct(private readonly ApieFacadeInterface $apieFacade)
    {
    }

    public function __invoke(ApieContext $context, array $rawContents): ActionResponse
    {
        return ActionResponse::createRunSuccess(
            $this->ApieFacade,
            $context,
            [],
            []
        );
    }

    public static function getInputType(ReflectionClass $class): ReflectionType
    {
        return ReflectionTypeFactory::createReflectionType('string');
    }

    public static function getOutputType(ReflectionClass $class): ListOf
    {
        return new ListOf(new ReflectionClass(DropdownOption::class));
    }

    public static function getPossibleActionResponseStatuses(): ActionResponseStatusList
    {
        return new ActionResponseStatusList([
            ActionResponseStatus::SUCCESS
        ]);
    }

    public static function getDescription(ReflectionClass $class): string
    {
        return 'Returns dropdown options for a specific input';
    }

    public static function getTags(ReflectionClass $class): StringList
    {
        return new StringList([$class->getShortName()]);
    }

    public static function getRouteAttributes(ReflectionClass $class): array
    {
        return [
            ContextConstants::GET_OBJECT => true,
            ContextConstants::RESOURCE_NAME => $class->name,
        ];
    }
}