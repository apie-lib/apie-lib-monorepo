<?php


namespace Apie\Tests\ValueObjectPlugin\Normalizers;

use Apie\MockObjects\ValueObject;
use Apie\MockObjects\ValueObjects\MockValueObjectWithStringTrait;
use Apie\ValueObjectPlugin\Normalizers\ValueObjectNormalizer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Serializer;

class ValueObjectNormalizerTest extends TestCase
{
    private $testItem;

    private $serializer;

    protected function setUp(): void
    {
        $this->testItem = new ValueObjectNormalizer();
        $this->serializer = new Serializer([$this->testItem], [new JsonEncode(), new JsonDecode()]);
    }

    public function testNormalize()
    {
        $actual = $this->serializer->serialize(new ValueObject('test'), 'json');
        $expected = '"test"';
        $this->assertEquals($expected, $actual);
    }

    public function testDenormalize()
    {
        $actual = $this->serializer->deserialize('"test"', ValueObject::class, 'json');
        $expected = new ValueObject('test');
        $this->assertEquals($expected, $actual);
    }
}
