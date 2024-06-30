<?php
namespace Apie\Tests\Core\FileStorage;

use Apie\Core\FileStorage\LocalFileStorage;
use PHPUnit\Framework\TestCase;

class LocalFileStorageTest extends TestCase
{
    private array $cleaning = [];

    protected function tearDown(): void
    {
        foreach ($this->cleaning as $path) {
            system('rm -rf '. escapeshellarg($path));
        }
        $this->cleaning = [];
    }

    public function givenALocalFileStorage(): LocalFileStorage
    {
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('localfile');
        mkdir($path, recursive: true);
        $this->cleaning[] = $path;
        $fixturesPath = __DIR__ . '/../../fixtures/LocalFileStorage';
        copy($fixturesPath . '/example.txt', $path . '/example.txt');
        return new LocalFileStorage(['path' => $path]);
    }

    /**
     * @test
     */
    public function it_can_create_and_find_uploaded_file_with_symfony_uploaded_file()
    {
        $testItem = $this->givenALocalFileStorage();
        $uploadedFile = $testItem->pathToUploadedFile('example.txt');
        $this->assertEquals('Lorem ipsum', $uploadedFile->getContent());
        $this->assertSame('example.txt', $testItem->uploadedFileToPath($uploadedFile));
    }

    /**
     * @test
     */
    public function it_can_create_and_find_uploaded_file_with_psr_uploaded_file()
    {
        $testItem = $this->givenALocalFileStorage();
        $uploadedFile = $testItem->pathToPsr('test.png');
        $this->assertEquals('text/plain', $uploadedFile->getClientMediaType());
        $this->assertSame('test.png', $testItem->psrToPath($uploadedFile));
    }
}
