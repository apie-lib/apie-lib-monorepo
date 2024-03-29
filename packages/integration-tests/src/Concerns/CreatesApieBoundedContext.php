<?php
namespace Apie\IntegrationTests\Concerns;

use Apie\Common\ValueObjects\EntityNamespace;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\CountryAndPhoneNumber\DutchPhoneNumber;
use Apie\IntegrationTests\Apie\TypeDemo\Entities\Ostrich;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\AnimalIdentifier;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\UserIdentifier;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\Animal;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\Order;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\PrimitiveOnly;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\User;
use Apie\IntegrationTests\Config\BoundedContextConfig;
use Apie\IntegrationTests\Requests\ActionMethodApiCall;
use Apie\IntegrationTests\Requests\CmsFormSubmitRequest;
use Apie\IntegrationTests\Requests\GetResourceApiCall;
use Apie\IntegrationTests\Requests\JsonFields\GetAndSetObjectField;
use Apie\IntegrationTests\Requests\JsonFields\GetAndSetPrimitiveField;
use Apie\IntegrationTests\Requests\JsonFields\GetPrimitiveField;
use Apie\IntegrationTests\Requests\JsonFields\GetUuidField;
use Apie\IntegrationTests\Requests\TestRequestInterface;
use Apie\IntegrationTests\Requests\ValidCreateResourceApiCall;
use Apie\TextValueObjects\FirstName;

trait CreatesApieBoundedContext
{
    /**
     * For testing POST /user
     */
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

    /**
     * For testing GET /user/{id}
     */
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

    /**
     * For testing GET /animal/{id}
     */
    public function createGetAnimalTestRequest(): TestRequestInterface
    {
        // @phpstan-ignore-next-line
        $animal = new Ostrich(AnimalIdentifier::createRandom(), new FirstName('Albert'));
        return new GetResourceApiCall(
            new BoundedContextId('types'),
            Animal::class,
            $animal->getId()->toNative(),
            [$animal],
            new GetAndSetObjectField(
                '',
                new GetPrimitiveField('id', $animal->getId()->toNative()),
                new GetPrimitiveField('name', 'ostrich'),
                new GetPrimitiveField('animalName', 'Albert'),
                new GetPrimitiveField('capableOfFlying', false),
                new GetPrimitiveField('type', 'bird')
            )
        );
    }

    /**
     * For testing POST/primitiveOnly
     */
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
            discardRequestValidation: true //casting string to int is not documented in OpenAPI spec.
        );
    }

    /**
     * For testing POST /animal
     */
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

    /**
     * For testing POST /Order
     */
    public function createPostOrderTestRequest(): TestRequestInterface
    {
        return new ValidCreateResourceApiCall(
            new BoundedContextId('types'),
            Order::class,
            new GetAndSetObjectField(
                '',
                new GetPrimitiveField('id', 1),
                new GetAndSetObjectField(
                    'orderLineList',
                    new GetAndSetObjectField(
                        '0',
                        new GetAndSetPrimitiveField('description', 'First order line'),
                        new GetUuidField('id'),
                    ),
                    new GetAndSetObjectField(
                        '1',
                        new GetAndSetPrimitiveField('description', 'Second order line'),
                        new GetUuidField('id'),
                    )
                ),
            ),
        );
    }

    /**
     * For cms action /usr/{id}/block
     */
    public function createBlockUserFormSubmit(): CmsFormSubmitRequest
    {
        return new CmsFormSubmitRequest(
            new BoundedContextId('types'),
            User::class,
            'test@example.com',
            'block',
            [
                new User(new UserIdentifier('test@example.com'))
            ],
            [
                '_csrf' => 'string',
                'form[blockedReason]' => 'This is a test'
            ],
            '/cms/types/resource/User/test@example.com'
        );
    }

    /**
     * For testing /calc/1/plus/12
     */
    public function createCustomActionRequest(): TestRequestInterface
    {
        return new ActionMethodApiCall(
            new BoundedContextId('types'),
            'calc/1/plus/12',
            new GetPrimitiveField('', 13)
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
