<?php

namespace Apie\Tests\ObjectAccessNormalizer\ObjectAccess;

use Apie\ObjectAccessNormalizer\Interfaces\LocalizationAwareInterface;
use Apie\ObjectAccessNormalizer\ObjectAccess\SimpleLocalizationObjectAccess;
use Apie\Tests\ObjectAccessNormalizer\Mocks\LocalizationAwareClass;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\PropertyInfo\Type;

class LocalizationAwareObjectAccessTest extends TestCase
{
    public function testGetters()
    {
        $localizationAware = new class implements LocalizationAwareInterface {
            public $acceptLanguage = 'nl';

            public $contentLanguage = 'de';

            public function getAcceptLanguage(): ?string
            {
                return $this->acceptLanguage;
            }

            public function getContentLanguage(): ?string
            {
                return $this->contentLanguage;
            }
        };

        $reflClass = new ReflectionClass(LocalizationAwareClass::class);

        $testItem = new SimpleLocalizationObjectAccess($localizationAware);

        $expected = ['id' => new Type(Type::BUILTIN_TYPE_STRING)];
        $this->assertEquals($expected, $testItem->getConstructorArguments($reflClass));

        $expected = ['id', 'private', 'description'];
        $this->assertEquals($expected, $testItem->getGetterFields($reflClass));

        $expected = [new Type(Type::BUILTIN_TYPE_STRING)];
        $this->assertEquals($expected, $testItem->getGetterTypes($reflClass, 'description'));

        $expected = ['private', 'description'];
        $this->assertEquals($expected, $testItem->getSetterFields($reflClass));

        $expected = [new Type(Type::BUILTIN_TYPE_STRING)];
        $this->assertEquals($expected, $testItem->getSetterTypes($reflClass, 'description'));

        $instance = new LocalizationAwareClass('slug');
        $instance->setDescription('en', 'Description');
        $instance->setDescription('nl', 'Beschrijving');
        $instance->setDescription('se', 'Beskrivning');

        $this->assertEquals('slug', $testItem->getValue($instance, 'id'));
        $this->assertEquals('Beschrijving', $testItem->getValue($instance, 'description'));
        $localizationAware->acceptLanguage = 'se';
        $this->assertEquals('Beskrivning', $testItem->getValue($instance, 'description'));

        // setValue is using contentLanguage, so it sets the german description.
        $testItem->setValue($instance, 'description', 'Beschreibung');
        $this->assertEquals('Beskrivning', $testItem->getValue($instance, 'description'));
        $localizationAware->acceptLanguage = 'de';
        $this->assertEquals('Beschreibung', $testItem->getValue($instance, 'description'));

        $this->assertEquals('', $testItem->getDescription($reflClass, 'slug', true));
    }
}
