<?php
namespace Apie\MockObjects\ApiResources;

use Apie\MockObjects\ValueObject;
use Ramsey\Uuid\Uuid;
use Apie\Core\Annotations\ApiResource;
use Apie\CorePlugin\DataLayers\MemoryDataLayer;

/**
 * @ApiResource(
 *     persistClass=MemoryDataLayer::class,
 *     retrieveClass=MemoryDataLayer::class,
 *     context={
 *         "search": {
 *             "uuid": "string",
 *             "stringValue": "string",
 *             "valueObject": "string"
 *         }
 *     }
 * )
 */
class FullRestObject
{
    /**
     * @var Uuid
     */
    private $uuid;

    /**
     * @var string
     */
    public $stringValue;

    /**
     * @var ValueObject
     */
    public $valueObject;

    public function __construct(?Uuid $uuid = null)
    {
        $this->uuid = $uuid ?? Uuid::uuid4();
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }
}
