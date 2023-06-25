<?php
namespace Apie\Tests\DoctrineMetadataDriver;

use Apie\DoctrineMetadataDriver\ApieMetadataDriver;
use Apie\DoctrineMetadataDriver\Builder\ApieMetadataBuilder;
use Apie\Fixtures\BoundedContextFactory;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\EntityGenerator;
use Doctrine\ORM\Tools\SchemaValidator;
use PHPUnit\Framework\TestCase;

class ApieMetaDriverTest extends TestCase
{
    /**
     * @test
     */
    public function it_generates_valid_doctrine_schema()
    {
        $testItem = new ApieMetadataDriver(
            'ApieDoctrine\\Test\\',
            new ApieMetadataBuilder(),
            BoundedContextFactory::createHashmapWithMultipleContexts()
        );
        $config = ORMSetup::createConfiguration(true);
        $config->setMetadataDriverImpl($testItem);
        $connection = DriverManager::getConnection([
            'driver' => 'pdo_sqlite'
        ], $config);
        $entityManager = new EntityManager($connection, $config);

        $cmf = new \Doctrine\ORM\Tools\DisconnectedClassMetadataFactory();
        $cmf->setEntityManager($entityManager);
        $metadatas = $cmf->getAllMetadata();
        $entityGenerator = new EntityGenerator();

        $entityGenerator->setGenerateAnnotations(true);
        $entityGenerator->setGenerateStubMethods(true);
        $entityGenerator->setRegenerateEntityIfExists(true);
        $entityGenerator->setUpdateEntityIfExists(true);
        $entityGenerator->setNumSpaces(4);
        $entityGenerator->setBackupExisting(false);

        // Generating Entities
        $entityGenerator->generate($metadatas, __DIR__ . '/dummy');

        $tool = new SchemaValidator($entityManager);
        $this->assertEquals([], $tool->validateMapping());
        var_dump($entityManager);
        
    }
}