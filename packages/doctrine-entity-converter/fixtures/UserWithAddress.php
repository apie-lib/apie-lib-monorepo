<?php
namespace Test\RenderOnly;

use Apie\Core\Dto\DtoInterface;
use Apie\DoctrineEntityConverter\Embeddables\MixedType;
use Apie\DoctrineEntityConverter\Utils\Utils;
use Apie\Fixtures\Entities\UserWithAddress as OriginalDomainObject;
use Doctrine\ORM\Mapping as ORM;

class UserWithAddress implements DtoInterface
{
    #[ORM\Embedded(class: 'Apie\DoctrineEntityConverter\Embeddables\MixedType')]
    public MixedType $id;

    #[ORM\Embedded(class: 'Apie\DoctrineEntityConverter\Embeddables\MixedType')]
    public MixedType $password;

    #[ORM\Embedded(class: 'Apie\DoctrineEntityConverter\Embeddables\MixedType')]
    public MixedType $address;

    private function __construct()
    {
    }

    public static function createFrom(OriginalDomainObject $input): self
    {
        $instance = new self();

        Utils::setProperty(
            $instance,
            new \ReflectionProperty('Apie\\Fixtures\\Entities\\UserWithAddress', 'id'),
            MixedType::fromCode($input)
        );
        Utils::setProperty(
            $instance,
            new \ReflectionProperty('Apie\\Fixtures\\Entities\\UserWithAddress', 'password'),
            MixedType::fromCode($input)
        );
        Utils::setProperty(
            $instance,
            new \ReflectionProperty('Apie\\Fixtures\\Entities\\UserWithAddress', 'address'),
            MixedType::fromCode($input)
        );
        return $instance;
    }

    public function inject(OriginalDomainObject $instance, \ReflectionProperty $property): void
    {
        $this->id->inject($instance, new \ReflectionProperty('Apie\\Fixtures\\Entities\\UserWithAddress', 'id'));
        $this->password->inject($instance, new \ReflectionProperty('Apie\\Fixtures\\Entities\\UserWithAddress', 'password'));
        $this->address->inject($instance, new \ReflectionProperty('Apie\\Fixtures\\Entities\\UserWithAddress', 'address'));
    }
}
