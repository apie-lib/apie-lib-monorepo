<?php

namespace Apie\Tests\IntegrationTests\RestApi;

use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Other\UploadedFileFactory;
use Apie\Faker\Datalayers\FakerDatalayer;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\UploadedFileIdentifier;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\UploadedFile;
use Apie\IntegrationTests\IntegrationTestHelper;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\PhpunitMatrixDataProvider\MakeDataProviderMatrix;
use Generator;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class DownloadFileTest extends TestCase
{
    use MakeDataProviderMatrix;

    public function it_can_download_an_uploaded_file_provider(): Generator
    {
        yield from $this->createDataProviderFrom(
            new ReflectionMethod($this, 'it_can_download_an_uploaded_file'),
            new IntegrationTestHelper()
        );
    }

    /**
     * @runInSeparateProcess
     * @dataProvider it_can_download_an_uploaded_file_provider
     * @test
     */
    public function it_can_download_an_uploaded_file(
        TestApplicationInterface $testApplication
    ) {
        $testApplication->runApplicationTest(function () use ($testApplication) {
            $datalayer = $testApplication->getServiceContainer()->get('apie');
            $uploadedFile = new UploadedFile(
                UploadedFileIdentifier::createRandom(),
                UploadedFileFactory::createUploadedFileFromString('Lorem ipsum', 'test.txt', 'text/plain')
            );
            $uploadedFile = $datalayer->persistNew($uploadedFile, new BoundedContextId('types'));
            $response = $testApplication->httpRequestGet('http://localhost/api/types/UploadedFile/' . $uploadedFile->getId() . '/file');
            $this->assertEquals(200, $response->getStatusCode());
            if ($testApplication->getApplicationConfig()->getDatalayerImplementation()->name !== FakerDatalayer::class) {
                $this->assertEquals('Lorem ipsum', $response->getBody()->__toString());
                $this->assertEquals('text/plain; charset=UTF-8', $response->getHeaderLine('content-type'));
            }
            
        });
    }

    public function it_can_stream_an_uploaded_file_provider(): Generator
    {
        yield from $this->createDataProviderFrom(
            new ReflectionMethod($this, 'it_can_stream_an_uploaded_file'),
            new IntegrationTestHelper()
        );
    }

    /**
     * @runInSeparateProcess
     * @dataProvider it_can_stream_an_uploaded_file_provider
     * @test
     */
    public function it_can_stream_an_uploaded_file(
        TestApplicationInterface $testApplication
    ) {
        $testApplication->runApplicationTest(function () use ($testApplication) {
            $datalayer = $testApplication->getServiceContainer()->get('apie');
            $uploadedFile = new UploadedFile(
                UploadedFileIdentifier::createRandom(),
                UploadedFileFactory::createUploadedFileFromString('Lorem ipsum', 'test.txt', 'text/plain')
            );
            $uploadedFile = $datalayer->persistNew($uploadedFile, new BoundedContextId('types'));
            $response = $testApplication->httpRequestGet('http://localhost/api/types/UploadedFile/' . $uploadedFile->getId() . '/stream');
            $this->assertEquals(200, $response->getStatusCode());
            if ($testApplication->getApplicationConfig()->getDatalayerImplementation()->name !== FakerDatalayer::class) {
                $this->assertEquals('Lorem ipsum', $response->getBody()->__toString());
                $this->assertEquals('application/octet-stream', $response->getHeaderLine('content-type'));
            }
            
        });
    }
}
