<?php


namespace Apie\Tests\ObjectAccessNormalizer\Normalizers;

use Apie\ObjectAccessNormalizer\Normalizers\ApieObjectAccessNormalizer;
use Apie\ObjectAccessNormalizer\Normalizers\MethodCallDenormalizer;
use Apie\ObjectAccessNormalizer\ObjectAccess\ObjectAccess;
use Apie\ObjectAccessNormalizer\ObjectAccess\ObjectAccessInterface;
use Apie\Tests\ObjectAccessNormalizer\Mocks\ClassWithConflictingTypehints;
use Apie\Tests\ObjectAccessNormalizer\Mocks\SumExample;
use Apie\UuidPlugin\Normalizers\UuidNormalizer;
use Apie\ValueObjectPlugin\Normalizers\ValueObjectNormalizer;
use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;

class MethodCallDenormalizerTest extends TestCase
{
    private function createSerializer(ObjectAccessInterface $objectAccess = null, NameConverterInterface $nameConverter = null): Serializer
    {
        $factory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $accessNormalizer = new ApieObjectAccessNormalizer($objectAccess, $nameConverter, $factory);
        return new Serializer(
            [
                new UuidNormalizer(),
                new DateTimeNormalizer(),
                new ValueObjectNormalizer(),
                new MethodCallDenormalizer($objectAccess ?? new ObjectAccess(), $accessNormalizer, $nameConverter),
                $accessNormalizer,
                new ArrayDenormalizer(),
            ],
            [new JsonEncoder()]
        );
    }

    public function testHappyFlow()
    {
        $testItem = $this->createSerializer();
        $input = new ClassWithConflictingTypehints(5.0);
        $result = $testItem->denormalize(
            [
                'boolean' => true,
                'sumExample' => [
                    'one' => 2,
                    'two' => 4,
                ]
            ],
            'ReflectionMethod::' . __CLASS__ . '::methodCall',
            null,
            [
                'object-instance' => $this,
                'initial-arguments' => [
                    'input' => $input
                ]
            ]
        );
        $this->assertEquals(
            [
                'resource' => $input,
                'is_true' => true,
                'sum' => new SumExample(2, 4),
            ],
            $result
        );
    }

    public function methodCall(ClassWithConflictingTypehints $input, bool $boolean, SumExample $sumExample)
    {
        return [
            'resource' => $input,
            'is_true' => $boolean,
            'sum' => $sumExample
        ];
    }
}
