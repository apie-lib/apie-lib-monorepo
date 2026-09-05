<?php
namespace Apie\Fixtures\TestHelpers;

use Apie\Core\Entities\EntityInterface;
use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Core\Identifiers\Ulid;
use ReflectionClass;

class EntityWithPropertyFactory
{
    private const NAMESPACE = 'FixtureTest';

    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }
    /**
     * @return ReflectionClass<EntityInterface>
     */
    public static function createEntityWithProperty(string $property): ReflectionClass
    {
        $className = 'Entity' . md5($property);
        if (!class_exists($className)) {
            $code = (
                "<?php
namespace " . self::NAMESPACE . ";

use " . EntityInterface::class . ";
use " . IdentifierInterface::class . ";
use " . Ulid::class . ";
use ReflectionClass;

class " . $className . "Identifier extends Ulid implements IdentifierInterface
{
    public static function getReferenceFor(): ReflectionClass
    {
        return new ReflectionClass(" . $className . "::class);
    }
}

class " . $className . " implements EntityInterface
{
    private ". $className . "Identifier \$id;
    public function __construct(
        
        public \\" . $property . " \$property
    ) {
        \$this->id = " . $className . "Identifier::createRandom();
    }

    public function getId(): " . $className . "Identifier
    {
        return \$this->id;
    }
}
"
            );
            $tmpPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'code' . md5($code) . '.php';
            if (!file_exists($tmpPath)) {
                file_put_contents($tmpPath, $code);
            }
            require_once($tmpPath);
        }

        return new \ReflectionClass(self::NAMESPACE . '\\' . $className);
    }
}
