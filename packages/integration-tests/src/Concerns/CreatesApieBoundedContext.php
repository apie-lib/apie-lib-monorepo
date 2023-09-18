<?php
namespace Apie\IntegrationTests\Concerns;

use Apie\Common\ValueObjects\EntityNamespace;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\CountryAndPhoneNumber\DutchPhoneNumber;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\UserIdentifier;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\Animal;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\PrimitiveOnly;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\User;
use Apie\IntegrationTests\Config\BoundedContextConfig;
use Apie\IntegrationTests\Requests\GetResourceApiCall;
use Apie\IntegrationTests\Requests\JsonFields\GetAndSetObjectField;
use Apie\IntegrationTests\Requests\JsonFields\GetAndSetPrimitiveField;
use Apie\IntegrationTests\Requests\JsonFields\GetPrimitiveField;
use Apie\IntegrationTests\Requests\TestRequestInterface;
use Apie\IntegrationTests\Requests\ValidCreateResourceApiCall;

trait CreatesApieBoundedContext
{
    public function createPostUserTestRequest(): TestRequestInterface
    {
        return new ValidCreateResourceApiCall(
            new BoundedContextId('types'),
            User::class,
            new GetAndSetObjectField(
                '',
                new GetAndSetPrimitiveField('id', 'test@example.com'),
                new GetPrimitiveField('blocked', false),
                new GetPrimitiveField('blockedReason', null),
                new GetAndSetPrimitiveField('phoneNumber', ' 0611223344 ', '+31611223344'),
            ),
        );
    }

    public function createGetUserTestRequest(): TestRequestInterface
    {
        // @phpstan-ignore-next-line
        $user = (new User(UserIdentifier::fromNative('test@example.com')))->setPhoneNumber(DutchPhoneNumber::fromNative('0611223344'));
        return new GetResourceApiCall(
            new BoundedContextId('types'),
            User::class,
            'test@example.com',
            [$user],
            new GetAndSetObjectField(
                '',
                new GetPrimitiveField('id', 'test@example.com'),
                new GetPrimitiveField('blocked', false),
                new GetPrimitiveField('blockedReason', null),
                new GetPrimitiveField('phoneNumber', '+31611223344'),
            )
        );
    }

    public function createPostPrimitiveOnlyTestRequest(): TestRequestInterface
    {
        return new ValidCreateResourceApiCall(
            new BoundedContextId('types'),
            PrimitiveOnly::class,
            new GetAndSetObjectField(
                '',
                new GetAndSetPrimitiveField('id', '550e8400-e29b-41d4-a716-446655440000'),
                new GetAndSetPrimitiveField('stringField', 'test'),
                new GetAndSetPrimitiveField('integerField', '42', 42),
                new GetAndSetPrimitiveField('floatingPoint', 1.5, 1.5),
                new GetPrimitiveField('booleanField', null),
            ),
        );
    }

    public function createPostAnimalTestRequest(): TestRequestInterface
    {
        return new ValidCreateResourceApiCall(
            new BoundedContextId('types'),
            Animal::class,
            new GetAndSetObjectField(
                '',
                new GetAndSetPrimitiveField('id', '550e8400-e29b-41d4-a716-446655440000'),
                new GetAndSetPrimitiveField('animalName', 'George', 'George'),
                new GetAndSetPrimitiveField('type', 'mammal'),
                new GetAndSetPrimitiveField('name', 'human'),
                new GetPrimitiveField('capableOfLayingEggs', false),
                new GetAndSetPrimitiveField('lastName', 'Pig'),
            ),
        );
    }

    public function createExampleBoundedContext(): BoundedContextConfig
    {
        $result = new BoundedContextConfig();
        $result->addEntityNamespace(
            new BoundedContextId('types'),
            __DIR__ . '/../Apie/TypeDemo',
            new EntityNamespace('Apie\\IntegrationTests\\Apie\\TypeDemo\\')
        );

        return $result;
    }
}
