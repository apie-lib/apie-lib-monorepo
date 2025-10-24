<?php
namespace Apie\IntegrationTests;

use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Entities\EntityInterface;
use Apie\IntegrationTests\Apie\TypeDemo\Entities\Ostrich;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\AnimalIdentifier;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\PrimitiveOnlyIdentifier;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\PrimitiveOnly;
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

    /**
     * @return array<int, EntityInterface>
     */
    private function createEntityList(): array
    {
        return [
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
        ];
    }

    public function createListCallWithDepthThree(): WebdavTestRequestInterface
    {
        return new ListFilesWebdavCall(
            new BoundedContextId('types'),
            3,
            entities: $this->createEntityList()
        );
    }

    public function createListCallOnSubfolder(): WebdavTestRequestInterface
    {
        return new ListFilesWebdavCall(
            new BoundedContextId('types'),
            pathSuffix: '/resources'
        );
    }

    public function createPaginationCall(): WebdavTestRequestInterface
    {
        return new ListFilesWebdavCall(
            new BoundedContextId('types'),
            6,
            entities: $this->createEntityList(),
            pathSuffix: '/resources/Animal/0'
        );
    }

    public function createResourceCallOnSubfolder(): WebdavTestRequestInterface
    {
        return new ListFilesWebdavCall(
            new BoundedContextId('types'),
            6,
            entities: $this->createEntityList(),
            pathSuffix: '/resources/Animal/0/00000000-0000-0000-0000-000000000000.json'
        );
    }

    public function createUploadCall(): WebdavTestRequestInterface
    {
        return new UploadFileWebdavCall(new BoundedContextId('types'));
    }
}
