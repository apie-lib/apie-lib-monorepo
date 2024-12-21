<?php
namespace Apie\IntegrationTests\Concerns;

use Apie\Common\ValueObjects\EntityNamespace;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\CountryAndPhoneNumber\DutchPhoneNumber;
use Apie\IntegrationTests\Apie\TypeDemo\Entities\Ostrich;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\AnimalIdentifier;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\RestrictedEntityIdentifier;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\UserIdentifier;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\Animal;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\Order;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\PrimitiveOnly;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\RestrictedEntity;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\UploadedFile;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\User;
use Apie\IntegrationTests\Config\BoundedContextConfig;
use Apie\IntegrationTests\Console\InteractiveConsoleCommand;
use Apie\IntegrationTests\Requests\ActionMethodApiCall;
use Apie\IntegrationTests\Requests\CmsFormSubmitRequest;
use Apie\IntegrationTests\Requests\GetResourceApiCall;
use Apie\IntegrationTests\Requests\GetResourceApiCallDenied;
use Apie\IntegrationTests\Requests\GetResourceListApiCall;
use Apie\IntegrationTests\Requests\JsonFields\GetAndSetObjectField;
use Apie\IntegrationTests\Requests\JsonFields\GetAndSetPrimitiveField;
use Apie\IntegrationTests\Requests\JsonFields\GetAndSetUploadedFileField;
use Apie\IntegrationTests\Requests\JsonFields\GetPrimitiveField;
use Apie\IntegrationTests\Requests\JsonFields\GetUuidField;
use Apie\IntegrationTests\Requests\JsonFields\SetPrimitiveField;
use Apie\IntegrationTests\Requests\TestRequestInterface;
use Apie\IntegrationTests\Requests\ValidCreateResourceApiCall;
use Apie\TextValueObjects\CompanyName;
use Apie\TextValueObjects\FirstName;

/**
 * @codeCoverageIgnore
 */
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
     * For testing POST /UploadedFile
     */
    public function createPostUploadedFileTestRequest(): TestRequestInterface
    {
        return new ValidCreateResourceApiCall(
            new BoundedContextId('types'),
            UploadedFile::class,
            new GetAndSetObjectField(
                '',
                new GetAndSetPrimitiveField('id', '550e8400-e29b-41d4-a716-446655440000'),
                new GetAndSetUploadedFileField(
                    'file',
                    'first order line',
                    'test-evil.txt',
                    '/types/UploadedFile/550e8400-e29b-41d4-a716-446655440000/download/file'
                ),
                new GetAndSetUploadedFileField(
                    'imageFile',
                    file_get_contents(__DIR__ . '/../../fixtures/apie-logo.svg'),
                    'apie-logo.svg',
                    '/types/UploadedFile/550e8400-e29b-41d4-a716-446655440000/download/imageFile'
                ),
                new GetPrimitiveField('stream', '/types/UploadedFile/550e8400-e29b-41d4-a716-446655440000/download/stream')
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
     * Test for dropdown action for comboboxes on entity create
     *
     * Url POST /ObjectWithRelation/dropdown-options/userId
     */
    public function createPropertyOptionsTestRequest(): TestRequestInterface
    {
        $user = new User(UserIdentifier::fromNative('test@example.com'));
        return new ActionMethodApiCall(
            new BoundedContextId('types'),
            'ObjectWithRelation/dropdown-options/userId',
            new GetAndSetObjectField(
                '',
                new SetPrimitiveField('input', 'test@'),
                new GetAndSetObjectField(
                    '0',
                    new GetPrimitiveField('value', 'test@example.com'),
                    new GetPrimitiveField('displayValue', 'test@example.com'),
                )
            ),
            entities: [$user],
            discardValidationOnFaker: true
        );
    }

    /**
     * Test for entity with permission restrictions.
     *
     * POST /RestrictedEntity
     */
    public function createObjectWithRestrictionTestRequest(): TestRequestInterface
    {
        return new ValidCreateResourceApiCall(
            new BoundedContextId('types'),
            RestrictedEntity::class,
            new GetAndSetObjectField(
                '',
                new GetAndSetPrimitiveField('id', '550e8400-e29b-41d4-a716-446655440000'),
                new GetAndSetPrimitiveField('companyName', 'Company NV'),
                new GetPrimitiveField('userId', null),
                new GetPrimitiveField('requiredPermissions', [])
            ),
        );
    }

    /**
     * Test for entity with permission restrictions.
     *
     * GET /RestrictedEntity
     */
    public function getObjectWithRestrictionTestRequest(): TestRequestInterface
    {
        $object = new RestrictedEntity(
            RestrictedEntityIdentifier::fromNative('550e8400-e29b-41d4-a716-446655440000'),
            new CompanyName('Company NV'),
            null
        );
        return new GetResourceApiCall(
            new BoundedContextId('types'),
            RestrictedEntity::class,
            '550e8400-e29b-41d4-a716-446655440000',
            [$object],
            new GetAndSetObjectField(
                '',
                new GetAndSetPrimitiveField('id', '550e8400-e29b-41d4-a716-446655440000'),
                new GetAndSetPrimitiveField('companyName', 'Company NV'),
                new GetPrimitiveField('userId', null),
                new GetPrimitiveField('requiredPermissions', [])
            ),
            discardValidationOnFaker: true
        );
    }

    /**
     * Test for entity with permission restrictions.
     *
     * GET /RestrictedEntity/{id}
     */
    public function getObjectWithRestrictionDeniedTestRequest(): TestRequestInterface
    {
        $userId = UserIdentifier::fromNative('user@example.com');
        $user = new User($userId);
        $object = new RestrictedEntity(
            RestrictedEntityIdentifier::fromNative('550e8400-e29b-41d4-a716-446655440000'),
            new CompanyName('Company NV'),
            $user
        );
        return new GetResourceApiCallDenied(
            new BoundedContextId('types'),
            RestrictedEntity::class,
            '550e8400-e29b-41d4-a716-446655440000',
            [$object, $user],
            new GetAndSetObjectField(
                '',
                new GetAndSetPrimitiveField('id', '550e8400-e29b-41d4-a716-446655440000'),
                new GetAndSetPrimitiveField('companyName', 'Company NV'),
                new GetPrimitiveField('userId', 'user@example.com'),
                new GetPrimitiveField('requiredPermissions', ['useratexampledotcom:read', 'useratexampledotcom:write'])
            ),
            discardValidationOnFaker: true
        );
    }

    /**
     * Test for entity list with permission restrictions.
     *
     * GET /RestrictedEntity/
     */
    public function getObjectWithRestrictionListTestRequest(): TestRequestInterface
    {
        $userId = UserIdentifier::fromNative('user@example.com');
        $user = new User($userId);
        $object = new RestrictedEntity(
            RestrictedEntityIdentifier::fromNative('550e8400-e29b-41d4-a716-446655440000'),
            new CompanyName('Company NV'),
            $user
        );
        $object2 = new RestrictedEntity(
            RestrictedEntityIdentifier::fromNative('550e8400-e29b-41d4-a716-446655440001'),
            new CompanyName('Company NV 2'),
            null
        );
        return new GetResourceListApiCall(
            new BoundedContextId('types'),
            RestrictedEntity::class,
            [$object, $object2, $user],
            new GetAndSetObjectField(
                '',
                new GetPrimitiveField('totalCount', 1),
                new GetPrimitiveField('filteredCount', 1),
                new GetPrimitiveField('first', '/types/RestrictedEntity'),
                new GetPrimitiveField('last', '/types/RestrictedEntity'),
                new GetAndSetObjectField(
                    'list',
                    new GetAndSetObjectField(
                        '0',
                        new GetAndSetPrimitiveField('id', '550e8400-e29b-41d4-a716-446655440001'),
                        new GetAndSetPrimitiveField('companyName', 'Company NV 2'),
                        new GetPrimitiveField('requiredPermissions', []),
                        new GetPrimitiveField('userId', null),
                    )
                ),
            ),
            discardValidationOnFaker: true
        );
    }

    /**
     * Test for invalid property for comboboxes on entity create
     *
     * Url POST /ObjectWithRelation/dropdown-options/unknown
     */
    public function createInvalidPropertyOptionsTestRequest(): TestRequestInterface
    {
        return new ActionMethodApiCall(
            new BoundedContextId('types'),
            'ObjectWithRelation/dropdown-options/unknown',
            new GetAndSetObjectField(
                '',
                new SetPrimitiveField('input', 'test@'),
            ),
            entities: [],
        );
    }

    /**
     * Test for dropdown action for comboboxes on action method call.
     */
    public function createMethodArgumentOptionsTestRequest(): TestRequestInterface
    {
        $user = new User(UserIdentifier::fromNative('test@example.com'));
        return new ActionMethodApiCall(
            new BoundedContextId('types'),
            'action/Authentication/isThisMe/dropdown-options/userId',
            new GetAndSetObjectField(
                '',
                new SetPrimitiveField('input', 'test@'),
                new GetAndSetObjectField(
                    '0',
                    new GetPrimitiveField('value', 'test@example.com'),
                    new GetPrimitiveField('displayValue', 'test@example.com'),
                )
            ),
            entities: [$user],
            discardValidationOnFaker: true
        );
    }

    /**
     * For cms action /user/{id}/block
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

    public function createSimpleConsoleInteraction(): InteractiveConsoleCommand
    {
        return new InteractiveConsoleCommand(
            'apie:types:primitive-only:create',
            PrimitiveOnly::class,
            [
                'stringField' => [0, 'string'],
                'integerField' => [0, 42],
                'floatingPoint' => [0, 1.5],
                'booleanField' => [0, 'yes'],
                'id' => ['075433c9-ca1f-435c-be81-61bae3009521']
            ]
        );
    }

    public function createOrderLineInteraction(): InteractiveConsoleCommand
    {
        return new InteractiveConsoleCommand(
            'apie:types:order:create',
            Order::class,
            [
                'orderLineList' => ['yes', 'my order line description', 'no'],
            ]
        );
    }

    public function createFileUploadInteraction(): InteractiveConsoleCommand
    {
        return new InteractiveConsoleCommand(
            'apie:types:uploaded-file:create',
            UploadedFile::class,
            [
                'id' => ['075433c9-ca1f-435c-be81-61bae3009521'],
                'file' => [__FILE__],
                'imageFile' => ['1'],
            ]
        );
    }

    /*public function createPolymorphicObjectInteraction(): InteractiveConsoleCommand
    {
        return new InteractiveConsoleCommand(
            'apie:types:animal:create',
            Animal::class,
            [
                'type' => ['mammal'],
                'name' => ['human'],
                'id' => ['075433c9-ca1f-435c-be81-61bae3009521'],
                'animalName' => ['Donald'],
                'lastName' => ['Duck'],
            ]
        );
    }  */

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
