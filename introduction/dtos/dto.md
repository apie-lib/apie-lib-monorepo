# Data Transfer Objects
Data transfer objects (or DTO's) are just objects with public properties with no business logic at all. Union types can be used on properties to allow multiple types. Most Apie components assume all properties are required, but with the Optional attribute it is possible to tell Apie that a property is optional. To tell that an object should be treated as a DTO it should implement the marker interface <code>Apie\Core\Dto\DtoInterface</code> so Apie knows this is a Data Transfer Object.

```php
use Apie\Core\Dto\DtoInterface;
use PrinsFrank\Standards\Country\ISO3166_1_Alpha_2;

class Address implements DtoInterface {
    public Street $street; // Street is some value object
    public ?StreetNumber $streetNumber = null; // StreetNumber is some value object, but not all addresses have a street number
    public Location $location;
    public ISO3166_1_Alpha_2 $country; // use a country enum from https://github.com/PrinsFrank/standards
}
```

It is also possible to use promoted properties:
```php
use Apie\Core\Dto\DtoInterface;
use PrinsFrank\Standards\Country\ISO3166_1_Alpha_2;

class Address implements DtoInterface {
    public function __construct(
        public Street $street,
        public ?StreetNumber $streetNumber = null,
        public Location $location,
        public ISO3166_1_Alpha_2 $country
    ) {
    }
}
```

## Optional fields
By default all fields are considered required, unless it has a default value or if the Optional attribute is assigned to a property:

```php
use Apie\Core\Attributes\Optional;
use Apie\Core\Dto\DtoInterface;
use PrinsFrank\Standards\Country\ISO3166_1_Alpha_2;

class Address implements DtoInterface {
    public Street $street; // Street is some value object
    public ?StreetNumber $streetNumber = null; // StreetNumber is some value object, but not all addresses have a street number
    #[Optional]
    public string $streetNumberSuffix; // field is optional and has no default value. If it is not given, the property is also not set!
    public Location $location;
    public ISO3166_1_Alpha_2 $country; // use a country enum from https://github.com/PrinsFrank/standards
}
```
While it is possible, adding #[Optional] attributes to a promoted property with no default value stil marks the field as optional even if it is required in the constructor as Apie will create DTO's with [newInstanceWithoutConstructor](https://www.php.net/manual/en/reflectionclass.newinstancewithoutconstructor.php) and will ignore the constructor definition.
