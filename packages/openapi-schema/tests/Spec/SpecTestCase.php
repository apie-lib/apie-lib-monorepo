<?php


namespace Apie\Tests\OpenapiSchema\Spec;

use Apie\ValueObjects\ValueObjectInterface;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\Finder\Finder;

abstract class SpecTestCase extends TestCase
{
    /**
     * How to create the value object. $input is from the data provider.
     */
    abstract protected function createFromInput($input): ValueObjectInterface;

    /**
     * Returns folder with all test cases for this test.
     */
    abstract protected function getFolderName(): string;

    /**
     * For invalid testcases we have a folder per exception class. The test requires a method to map folders
     * to the expected exception class.
     */
    abstract protected function mapFolderToException(string $folderName): string;

    /**
     * @dataProvider validInputProvider
     */
    public function testFromNative_valid_input(array $expected, $input)
    {
        $testItem = $this->createFromInput($input);
        $this->assertEquals($expected, $testItem->toNative());
    }

    public function validInputProvider()
    {
        $folderName = $this->getFolderName();
        $folder = __DIR__ . '/../fixtures/testCases/' . $folderName . '/valid';
        if (!file_exists($folder)) {
            throw new RuntimeException('Folder ' . $folder . ' does not exist!');
        }
        foreach (Finder::create()->files()->in($folder) as $file) {
            $input = json_decode(file_get_contents($file), true);
            yield [$input, $input, $file->getBasename('.json')];
        }
    }

    /**
     * @dataProvider invalidInputProvider
     */
    public function testFromNative_invalid_input(string $expectedExceptionClass, $input)
    {
        $this->expectException($expectedExceptionClass);
        $this->createFromInput($input);
    }

    public function invalidInputProvider()
    {
        $folderName = $this->getFolderName();
        $folder = __DIR__ . '/../fixtures/testCases/' . $folderName . '/invalid';
        if (!file_exists($folder)) {
            throw new RuntimeException('Folder ' . $folder . ' does not exist!');
        }
        foreach (Finder::create()->files()->in($folder) as $file) {
            $exceptionClass = $this->mapFolderToException(basename($file->getPath()));
            $input = json_decode(file_get_contents($file), true);
            yield [$exceptionClass, $input, $file->getBasename('.json')];
        }
    }
}
