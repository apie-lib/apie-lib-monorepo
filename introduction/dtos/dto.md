# DTO's
DTO's are just objects with public properties. Union types can be used on properties for example to allow multiple types. Most Apie components assume all properties are required, but with the Optional attribute it is possible to tell Apie that a property is optional. To tell that an object should be treated as a DTO it should implement the marker interface <code>Apie\Core\Dto\DtoInterface</code>

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
