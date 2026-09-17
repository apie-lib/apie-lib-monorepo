<?php
namespace Apie\Webdav\Tests\Dav;

use Apie\ApieFileSystem\Lists\VirtualFileMap;
use Apie\ApieFileSystem\Virtual\VirtualFileInterface;
use Apie\ApieFileSystem\Virtual\VirtualFolderInterface;
use Apie\Webdav\Dav\ApieDirectory;
use Apie\Webdav\Dav\ApieFile;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ApieDirectoryTest extends TestCase
{
    #[Test]
    public function it_wraps_virtaul_folder()
    {
        $fileMock = $this->createMock(VirtualFileInterface::class);
        $fileMock->method('getName')->willReturn('file.txt');
        $fileMock->method('getContents')->willReturn('content');
        $fileMock->method('getSize')->willReturn(7);
        $fileMock->method('getMimeType')->willReturn('text/plain');

        $folderMock = $this->createMock(VirtualFolderInterface::class);
        $folderMock->method('getName')->willReturn('folder');

        $fileMap = new VirtualFileMap(['file.txt' => $fileMock]);
        $folderMock->method('getChildren')->willReturn($fileMap);
        $folderMock->method('getChild')->willReturnCallback(function ($name) use ($fileMock) {
            return $name === 'file.txt' ? $fileMock : null;
        });

        $directory = new ApieDirectory($folderMock);

        $children = $directory->getChildren();
        $this->assertCount(1, $children);
        $this->assertInstanceOf(ApieFile::class, $children[0]);
        $this->assertEquals('file.txt', $children[0]->getName());

        $child = $directory->getChild('file.txt');
        $this->assertInstanceOf(ApieFile::class, $child);
        $this->assertEquals('file.txt', $child->getName());

        $this->assertTrue($directory->childExists('file.txt'));
        $this->assertFalse($directory->childExists('notfound.txt'));
        $this->assertEquals('folder', $directory->getName());
    }
}
