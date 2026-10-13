<?php
namespace Apie\Fixtures\TestHelpers;

use Apie\Core\BoundedContext\BoundedContext;
use Apie\Core\BoundedContext\BoundedContextHashmap;
use Apie\Core\FileStorage\FileStorageFactory;
use Apie\Core\Indexing\Indexer;
use Apie\Core\Lists\ReflectionClassList;
use Apie\Core\Lists\ReflectionMethodList;
use Apie\Core\ValueObjects\ValueObjectInterface;
use Apie\DoctrineEntityConverter\Factories\PersistenceLayerFactory;
use Apie\DoctrineEntityConverter\OrmBuilder as DoctrineEntityConverterOrmBuilder;
use Apie\DoctrineEntityDatalayer\DoctrineEntityDatalayer;
use Apie\DoctrineEntityDatalayer\EntityReindexer;
use Apie\DoctrineEntityDatalayer\Factories\DoctrineListFactory;
use Apie\DoctrineEntityDatalayer\Factories\EntityQueryFilterFactory;
use Apie\DoctrineEntityDatalayer\IndexStrategy\DirectIndexStrategy;
use Apie\DoctrineEntityDatalayer\OrmBuilder;
use Apie\Faker\ApieObjectFaker;
use Apie\Fixtures\Attributes\DisableDatalayerTest;
use Apie\StorageMetadata\DomainToStorageConverter;
use Faker\Factory;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @mixin TestCase
 */
trait TestWithDoctrineEntityDatalayer
{
    /**
     * @return class-string<ValueObjectInterface>
     */
    abstract public static function className(): string;

    public static function createExampleObject(): object
    {
        if (!class_exists(Factory::class) || !class_exists(ApieObjectFaker::class)) {
            throw new \LogicException('The default createExampleObject() requires apie/faker to be available!');
        }
        $generator = Factory::create();
        $generator->addProvider(ApieObjectFaker::createWithDefaultFakers($generator));
        return $generator->fakeClass(static::className());
    }

    #[Test]
    public function it_can_be_stored_in_a_property_with_doctrine_entity_datalayer()
    {
        foreach ((new \ReflectionClass(static::class))->getAttributes() as $attribute) {
            if ($attribute->getName() === DisableDatalayerTest::class) {
                $this->markTestSkipped('DoctrineEntityDatalayer test disabled for this class');
            }
        }
        if (!class_exists(DoctrineEntityDatalayer::class)) {
            $this->markTestSkipped('DoctrineEntityDatalayer class not loaded');
        }
        $className = static::className();
        $entityClass = EntityWithPropertyFactory::createEntityWithProperty($className);
        $tempFolder = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('doctrine-');
        if (!@mkdir($tempFolder)) {
            $this->markTestSkipped('Can not create temp folder ' . $tempFolder);
        }
        try {
            $entityPath = $tempFolder . DIRECTORY_SEPARATOR . 'entities';
            if (!@mkdir($entityPath)) {
                $this->markTestSkipped('Can not create entity folder ' . $entityPath);
            }
            $proxyPath = $tempFolder . DIRECTORY_SEPARATOR . 'proxies';
            if (!@mkdir($proxyPath)) {
                $this->markTestSkipped('Can not create proxy folder ' . $proxyPath);
            }
            $ormBuilder = new DoctrineEntityConverterOrmBuilder(
                new PersistenceLayerFactory(),
                new BoundedContextHashmap([
                    'test' => new BoundedContext(
                        'test',
                        new ReflectionClassList([$entityClass]),
                        new ReflectionMethodList()
                    )
                ]),
                true
            );
            $ormBuilder = new OrmBuilder(
                $ormBuilder,
                buildOnce: false,
                runMigrations: true,
                devMode: true,
                proxyDir: $proxyPath,
                cache: null,
                path: $entityPath,
                connectionConfig: [
                    'driver' => 'pdo_sqlite',
                    'memory' => true
                ]
            );
            $domainToStorageConverter = DomainToStorageConverter::create(FileStorageFactory::create());
            $doctrineListFactory = new DoctrineListFactory(
                $ormBuilder,
                new EntityQueryFilterFactory(),
                $domainToStorageConverter
            );
            $testItem = new DoctrineEntityDatalayer(
                $ormBuilder,
                $domainToStorageConverter,
                new DirectIndexStrategy(new EntityReindexer($ormBuilder, Indexer::create())),
                $doctrineListFactory
            );
            $entity = $entityClass->newInstance($this->createExampleObject());
            $idBeforePersist = $entity->getId();
            $actual = $testItem->persistNew($entity);
            $this->assertEquals($idBeforePersist, $actual->getId()->toNative());
        } finally {
            system('rm -rf '  . escapeshellarg($tempFolder));
        }
    }
}
