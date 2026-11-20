<?php
namespace Apie\Webdav\Tests\Dav;

use Apie\ApieFileSystem\Virtual\VirtualFileInterface;
use Apie\Webdav\Dav\ApieFile;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ApieFileTest extends TestCase
{
    #[Test]
    public function it_wraps_virtual_file()
    {
        $mock = $this->createMock(VirtualFileInterface::class);
        $mock->method('getName')->willReturn('test.txt');
        $mock->method('getContents')->willReturn('Hello world!');
        $mock->method('getSize')->willReturn(12);
        $mock->method('getMimeType')->willReturn('text/plain');

        $apieFile = new ApieFile($mock);

        $this->assertEquals('test.txt', $apieFile->getName());
        $this->assertEquals('Hello world!', $apieFile->get());
        $this->assertEquals(12, $apieFile->getSize());
        $this->assertEquals(md5('Hello world!'), $apieFile->getETag());
        $this->assertEquals('text/plain', $apieFile->getContentType());
    }
}
