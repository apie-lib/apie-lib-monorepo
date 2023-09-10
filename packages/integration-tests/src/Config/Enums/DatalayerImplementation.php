<?php
namespace Apie\IntegrationTests\Config\Enums;

use Apie\Common\Wrappers\RequestAwareInMemoryDatalayer;
use Apie\Core\Datalayers\ApieDatalayer;
use Apie\DoctrineEntityDatalayer\DoctrineEntityDatalayer;
use ReflectionClass;

enum DatalayerImplementation: string
{
    case DB_DATALAYER = 'db';
    case IN_MEMORY = 'in_memory';

    /**
     * @return ReflectionClass<ApieDatalayer>
     */
    public function toClass(): ReflectionClass
    {
        return match($this) {
            self::DB_DATALAYER => new ReflectionClass(DoctrineEntityDatalayer::class),
            self::IN_MEMORY => new ReflectionClass(RequestAwareInMemoryDatalayer::class),
        };
    }
}
