<?php
namespace Apie\IntegrationTests;

use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\ValueObjects\Price;
use Apie\IntegrationTests\Apie\TypeDemo\Entities\Ostrich;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\AnimalIdentifier;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\PrimitiveOnlyIdentifier;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\PrimitiveOnly;
use Apie\IntegrationTests\IntegrationTestHelper;
use Apie\IntegrationTests\Requests\ListFilesWebdavCall;
use Apie\IntegrationTests\Requests\UploadFileWebdavCall;
use Apie\IntegrationTests\Requests\WebdavTestRequestInterface;
use Apie\TextValueObjects\FirstName;

class WebdavTestHelper extends IntegrationTestHelper
{
    public function createListCallWithDepthOne(): WebdavTestRequestInterface
    {
        return new ListFilesWebdavCall(new BoundedContextId('types'));
    }

    public function createListCallWithDepthThree(): WebdavTestRequestInterface
    {
        return new ListFilesWebdavCall(
            new BoundedContextId('types'),
            3,
            entities: [
                new Ostrich(
                    AnimalIdentifier::fromNative('00000000-0000-0000-0000-000000000000'),
                    FirstName::fromNative('Emu')
                ),
                new Ostrich(
                    AnimalIdentifier::fromNative('00000000-0000-0000-0000-000000000001'),
                    FirstName::fromNative('Emu')
                ),
                new PrimitiveOnly(
                    PrimitiveOnlyIdentifier::fromNative('00000000-0000-0000-0000-000000000002')
                )
            ]
        );
    }

    public function createListCallOnSubfolder(): WebdavTestRequestInterface
    {
        return new ListFilesWebdavCall(new BoundedContextId('types'), pathSuffix: '/resources');
    }

    public function createResourceCallOnSubfolder(): WebdavTestRequestInterface
    {
        return new ListFilesWebdavCall(
            new BoundedContextId('types'),
            3,
            entities: [
                new Ostrich(
                    AnimalIdentifier::fromNative('00000000-0000-0000-0000-000000000000'),
                    FirstName::fromNative('Emu')
                ),
                new Ostrich(
                    AnimalIdentifier::fromNative('00000000-0000-0000-0000-000000000001'),
                    FirstName::fromNative('Emu')
                ),
                new PrimitiveOnly(
                    PrimitiveOnlyIdentifier::fromNative('00000000-0000-0000-0000-000000000002')
                )
            ],
            pathSuffix: '/resources/Animal/00000000-0000-0000-0000-000000000000.json'
        );
    }

    public function createUploadCall(): WebdavTestRequestInterface
    {
        return new UploadFileWebdavCall(new BoundedContextId('types'));
    }
}