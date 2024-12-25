<?php

namespace Apie\Tests\IntegrationTests\RestApi;

use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\FileStorage\StoredFile;
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

    public static function it_can_download_an_uploaded_file_provider(): Generator
    {
        yield from self::createDataProviderFrom(
            new ReflectionMethod(__CLASS__, 'it_can_download_an_uploaded_file'),
            new IntegrationTestHelper()
        );
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('it_can_download_an_uploaded_file_provider')]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_download_an_uploaded_file(
        TestApplicationInterface $testApplication
    ) {
        $testApplication->ItRunsApplications(function () use ($testApplication) {
            $datalayer = $testApplication->getServiceContainer()->get('apie');
            $uploadedFile = new UploadedFile(
                UploadedFileIdentifier::createRandom(),
                StoredFile::createFromString('Lorem ipsum', 'text/plain', 'test.txt')
            );
            $uploadedFile = $datalayer->persistNew($uploadedFile, new BoundedContextId('types'));
            $response = $testApplication->httpRequestGet('http://localhost/api/types/UploadedFile/' . $uploadedFile->getId() . '/download/file');
            $this->assertEquals(200, $response->getStatusCode());
            if ($testApplication->getApplicationConfig()->getDatalayerImplementation()->name !== FakerDatalayer::class) {
                $this->assertEquals('Lorem ipsum', $response->getBody()->__toString());
                $this->assertEquals('text/plain; charset=UTF-8', $response->getHeaderLine('content-type'));
            }
            
        });
    }

    public static function it_can_stream_an_uploaded_file_provider(): Generator
    {
        yield from self::createDataProviderFrom(
            new ReflectionMethod(__CLASS__, 'it_can_stream_an_uploaded_file'),
            new IntegrationTestHelper()
        );
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('it_can_stream_an_uploaded_file_provider')]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_stream_an_uploaded_file(
        TestApplicationInterface $testApplication
    ) {
        $testApplication->ItRunsApplications(function () use ($testApplication) {
            $datalayer = $testApplication->getServiceContainer()->get('apie');
            $uploadedFile = new UploadedFile(
                UploadedFileIdentifier::createRandom(),
                StoredFile::createFromString('Lorem ipsum', 'text/plain', 'test.txt')
            );
            $uploadedFile = $datalayer->persistNew($uploadedFile, new BoundedContextId('types'));
            $response = $testApplication->httpRequestGet('http://localhost/api/types/UploadedFile/' . $uploadedFile->getId() . '/download/stream');
            $this->assertEquals(200, $response->getStatusCode());
            if ($testApplication->getApplicationConfig()->getDatalayerImplementation()->name !== FakerDatalayer::class) {
                $this->assertEquals('Lorem ipsum', $response->getBody()->__toString());
                $this->assertEquals('application/octet-stream', $response->getHeaderLine('content-type'));
            }
            
        });
    }
}
