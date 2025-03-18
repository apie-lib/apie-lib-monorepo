<?php
namespace App\ApiePlayground\Example\Enums;

use Apie\Common\Wrappers\RequestAwareInMemoryDatalayer;
use Apie\Core\Attributes\Internal;
use Apie\Core\Datalayers\ApieDatalayer;
use Apie\DoctrineEntityDatalayer\DoctrineEntityDatalayer;
use Apie\Faker\Datalayers\FakerDatalayer;
use ReflectionClass;
use RuntimeException;

enum DatalayerImplementation: string
{
    case DB_DATALAYER = 'Database';
    case IN_MEMORY = 'In memory';
    case FAKER = 'Faker';
    #[Internal]
    case OTHER = 'Other';

    /**
     * @return ReflectionClass<ApieDatalayer>
     */
    public function toClass(): ReflectionClass
    {
        return match($this) {
            self::DB_DATALAYER => new ReflectionClass(DoctrineEntityDatalayer::class),
            self::IN_MEMORY => new ReflectionClass(RequestAwareInMemoryDatalayer::class),
            self::FAKER => new ReflectionClass(FakerDatalayer::class),
            default => throw new RuntimeException('This is other'),
        };
    }

    /**
     * @param class-string<ApieDatalayer> $input
     */
    public static function fromClass(string $input): self 
    {
        return match($input) {
            DoctrineEntityDatalayer::class => self::DB_DATALAYER,
            RequestAwareInMemoryDatalayer::class => self::IN_MEMORY,
            FakerDatalayer::class => self::FAKER,
            default => self::OTHER,
        };
    }
}
