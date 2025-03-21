<img src="https://raw.githubusercontent.com/apie-lib/apie-lib-monorepo/main/docs/apie-logo.svg" width="100px" align="left" />
<h1>schema-generator</h1>






 [![Latest Stable Version](https://poser.pugx.org/apie/schema-generator/v)](https://packagist.org/packages/apie/schema-generator) [![Total Downloads](https://poser.pugx.org/apie/schema-generator/downloads)](https://packagist.org/packages/apie/schema-generator) [![Latest Unstable Version](https://poser.pugx.org/apie/schema-generator/v/unstable)](https://packagist.org/packages/apie/schema-generator) [![License](https://poser.pugx.org/apie/schema-generator/license)](https://packagist.org/packages/apie/schema-generator) [![PHP Composer](https://apie-lib.github.io/projectCoverage/coverage-schema-generator.svg)](https://apie-lib.github.io/projectCoverage/schema-generator/index.html)  

[![PHP Composer](https://github.com/apie-lib/schema-generator/actions/workflows/php.yml/badge.svg?event=push)](https://github.com/apie-lib/schema-generator/actions/workflows/php.yml)

This package is part of the [Apie](https://github.com/apie-lib) library.
The code is maintained in a monorepo, so PR's need to be sent to the [monorepo](https://github.com/apie-lib/apie-lib-monorepo/pulls)

## Documentation
The schema generator creates a JSON Schema from an object with typehints. It supports entities, lists, hashmaps, value objects and DTO made for Apie. It returns objects made with the library [cebe/php-openapi](https://github.com/cebe/php-openapi).

This library does not generate an entire OpenAPI schema, but instead it just creates the JSON schema section of all the objects.

### Standard usage
In general you make multiple schemas for multiple objects with references. Because of that we basically create a [Components section](https://spec.openapis.org/oas/v3.1.0#components-object)

Code example:
```php
<?php
use Apie\Fixtures\Enums\Gender;
use Apie\CommonValueObjects\Ranges\DateTimeRange;
use Apie\SchemaGenerator\ComponentsBuilderFactory;

$factory = ComponentsBuilderFactory::createComponentsBuilderFactory();
// $schema = ['type' => 'enum', 'enum' => ['M', 'V']]
$schema = $factory->addCreationSchemaFor(Gender::class);
/**
 * $schema = [
 *     'type' => 'object',
 *     'properties' => [
 *          'start' => ['$ref' => '#/components/schemas/DateWithTimezone-post'],
 *          'end' => ['$ref' => '#/components/schemas/DateWithTimezone-post'],
 *     ]
 * ]
 */
$schema = $factory->addCreationSchemaFor(DateTimeRange::class);
// $components = ['mixed', 'Gender-post', 'DateTimeRange-post', 'DateWithTimezone-post']
$components = array_keys($factory->getComponents()->schemas);
```

### DTO's
DTO's will be mapped as objects and all fields required unless it has the Optional attribute or a default value.

```php
<?php
use Apie\Core\Attributes\Optional;
use Apie\Core\Dto\DtoInterface;

class ExampleDto implements DtoInterface {
    string $example;

    int $number = 42;

    #[Optional()]
    Gender $gender;
}
```

Will Result in this schema:
```yaml
ExampleDto-post:
    required: ['example']
    properties:
        example:
            type: string
        number:
            type: number
        gender:
            $ref: "Gender-post"
```

### Enums
Enums will be mapped with type string or int and all the values in the enum property.

Backed enums will use the value of the enum.
Enums without values use the names and are always mapped as strings.

### Entities
Entities will be mapped by reading the constructor arguments and all the methods starting with set or with.

Any constructor argument is considered a required option,
unless it has a default value.

For all methods starting with set or with it expects the last argument to be the type needed. Other arguments are considered for contextual reasons (like being authenticated or the current locale).

### Value Objects
It tries to figure out what value objects the object is. For example if it uses one of the standard traits it will map those to objects or strings or read the toNative() return typehint.

- If it implements HasRegexValueObjectInterface, pattern is filled in.
- If it implements StringValueObjectInterface, type is filled in as string and the format is the name of the class without namespace.
- If the class uses the CompositeValueObject trait, it will be mapped as an object.

### Complete customization
You can add SchemaMethod attribute to a class and add a static method class to specify an openAPI schema for a class.

```php
<?php
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;
use Apie\Core\ValueObjects\IsStringValueObject;

#[SchemaMethod('getSchema')]
class Example implements StringValueObject {
    use IsStringValueObject;

    public static function getSchema(): array
    {
        return [
            'type' => 'string',
            'format' => 'password',
            'max' => 12,
        ];
    }
}
```
The method can return an instance of cebe\Openapi\Schema (you require cebe/php-openapi") or return an array of the schema.
