<?php
namespace Apie\CmsApiDropdownOption\Actions;

use Apie\CmsApiDropdownOption\DropdownOptionProvider\DropdownOptionProviderInterface;
use Apie\CmsApiDropdownOption\Dtos\PartialInput;
use Apie\CmsApiDropdownOption\Lists\DropdownOptionList;
use Apie\Core\Actions\ActionInterface;
use Apie\Core\Actions\ActionResponse;
use Apie\Core\Actions\ActionResponseStatus;
use Apie\Core\Actions\ActionResponseStatusList;
use Apie\Core\Actions\ApieFacadeInterface;
use Apie\Core\Context\ApieContext;
use Apie\Core\ContextConstants;
use Apie\Core\Lists\StringList;
use Apie\Core\Utils\EntityUtils;
use LogicException;
use ReflectionClass;
use ReflectionMethod;

class DropdownOptionsAction implements ActionInterface
{
    public function __construct(private readonly ApieFacadeInterface $apieFacade)
    {
    }

    public static function isAuthorized(ApieContext $context, bool $runtimeChecks, bool $throwError = false): bool
    {
        if ($context->hasContext(ContextConstants::RESOURCE_NAME)) {
            $refl = new ReflectionClass($context->getContext(ContextConstants::RESOURCE_NAME, $throwError));
            if (EntityUtils::isPolymorphicEntity($refl) && $runtimeChecks && $context->hasContext(ContextConstants::RESOURCE)) {
                $refl = new ReflectionClass($context->getContext(ContextConstants::RESOURCE, $throwError));
            }
        } else {
            $refl = new ReflectionClass($context->getContext(ContextConstants::SERVICE_CLASS));
        }
        return $context->appliesToContext($refl, $runtimeChecks, $throwError ? new LogicException('Class is not authorized') : null);
    }

    public function __invoke(ApieContext $context, array $rawContents): ActionResponse
    {
        $dropdownOptionProvider = $context->getContext(DropdownOptionProviderInterface::class);
        assert($dropdownOptionProvider instanceof DropdownOptionProviderInterface);
        $result = $dropdownOptionProvider->getList($context, $rawContents['input'] ?? '');
        return ActionResponse::createRunSuccess(
            $this->apieFacade,
            $context,
            $result,
            $result
        );
    }

    public static function getInputType(ReflectionClass $class): ReflectionClass
    {
        return new ReflectionClass(PartialInput::class);
    }

    /**
     * @return ReflectionClass<DropdownOptionList>
     */
    public static function getOutputType(ReflectionClass $class): ReflectionClass
    {
        return new ReflectionClass(DropdownOptionList::class);
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

    public static function getRouteAttributes(ReflectionClass $class, ?ReflectionMethod $method = null): array
    {
        if ($method !== null) {
            return [
                ContextConstants::SERVICE_CLASS => $class->name,
                ContextConstants::METHOD_NAME => $method->name,
            ];
        }
        return [
            ContextConstants::GET_OBJECT => true,
            ContextConstants::RESOURCE_NAME => $class->name,
        ];
    }
}
