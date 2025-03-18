<?php
namespace Apie\Tests\ApieCommonPlugin;

use Apie\ApieCommonPlugin\ObjectProvider;
use Apie\ApieCommonPlugin\ObjectProviderFactory;
use Apie\Core\Other\MockFileWriter;
use PHPUnit\Framework\TestCase;

class ObjectProviderFactoryTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function i_can_get_an_object_provider()
    {
        $object = ObjectProviderFactory::create(new MockFileWriter());
        $this->assertInstanceOf(ObjectProvider::class, $object);
    }
}
