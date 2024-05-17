<img src="https://raw.githubusercontent.com/apie-lib/apie-lib-monorepo/main/docs/apie-logo.svg" width="100px" align="left" />
<h1>common-value-objects</h1>






 [![Latest Stable Version](http://poser.pugx.org/apie/common-value-objects/v)](https://packagist.org/packages/apie/common-value-objects) [![Total Downloads](http://poser.pugx.org/apie/common-value-objects/downloads)](https://packagist.org/packages/apie/common-value-objects) [![Latest Unstable Version](http://poser.pugx.org/apie/common-value-objects/v/unstable)](https://packagist.org/packages/apie/common-value-objects) [![License](http://poser.pugx.org/apie/common-value-objects/license)](https://packagist.org/packages/apie/common-value-objects) [![PHP Version Require](http://poser.pugx.org/apie/common-value-objects/require/php)](https://packagist.org/packages/apie/common-value-objects) [![Code coverage](https://raw.githubusercontent.com/apie-lib/common-value-objects/main/coverage_badge.svg)](https://apie-lib.github.io/coverage/common-value-objects/index.html)  

[![PHP Composer](https://github.com/apie-lib/common-value-objects/actions/workflows/php.yml/badge.svg?event=push)](https://github.com/apie-lib/common-value-objects/actions/workflows/php.yml)

This package is part of the [Apie](https://github.com/apie-lib) library.
The code is maintained in a monorepo, so PR's need to be sent to the [monorepo](https://github.com/apie-lib/apie-lib-monorepo/pulls)

## Documentation
A set of common value object classes that can be used right away or can be used as examples how to make common value objects.

### Enums

enums are just PHP 8.1 enums with common values. They are fully supported by Apie.

| Enums | Description |
| --- | --- |
| Gender | Indicate male or female |

### Identifiers
Identifiers are used by Apie entities to tell the id of an entity. To use these as identifiers for your entity, you need to extend one of these and implement the IdentifierInterface. Otherwise you can also use them as property fields on an entity or a [composite value object](https://packagist.org/packages/apie/composite-value-objects).

Example:
```php
<?php
use Apie\Core\Identifiers\UuidV4;
use Apie\Core\Identifiers\IdentifierInterface;

class UserIdentifier extends UuidV4 implements IdentifierInterface
{
    public static function getReferenceFor(): ReflectionClass
    {
        return new ReflectionClass(User::class);
    }
}
```

```php
<?php
use Apie\Core\Entities\EntityInterface;

class User implements EntityInterface
{
    private UserIdentifier $id;

    public function __construct()
    {
        $this->id = UserIdentifier::createRandom();
    }

    public function getIdentifier(): UserIdentifier
    {
        return $this->id;
    }
}
```

| Class | Description |
| --- | --- |
| KebabCaseSlug | Slug in the shape of "example-slug" |
| PascalCaseSlug | Slug in the shape of "example_slug" | 
| Slug | Slug in the shape of "exampleslug" |
| Uuid | Any string in the shape of an uuid |
| UuidV1 | Uuid in version 1 format |
| UuidV2 | Uuid in version 2 format |
| UuidV3 | Uuid in version 3 format |
| UuidV4 | Uuid in version 4 format |
| UuidV5 | Uuid in version 5 format |
| UuidV6 | Uuid in version 6 format |

### Name value objects

Contains value objects related to names. They are very tolerant what is written
in them and support unicode characters to avoid wrong assumptions about
what is a valid first and last name.

| Class | Description |
| --- | --- |
| FirstName | First name |
| LastName | Last name |

### Range value objects

Ranges often have a restriction related that for example the start should always be lower than the end. They are often a composite of 2 values.

| Class | Description |
| --- | --- |
| DateTimeRange | A range between dates. The first date should always be lower than the last date |

### Text value objects

At first these value objects seem redundant as you wonder why you should not just use string, but the problem with using string is that it has no restriction on the length of a text. The value object will also mean you can tell an application it requires a non-empty text for example.

| Class | Description |
| --- | --- |
| NonEmptyString | Any string as long it is not empty. The text will be trimmed. |
| SmallDatabaseText | A string that fits into a database varchar field (255 characters). The text will be trimmed and can be empty. |
| StrongPasswordField | A string used for passwords that are considered strong passwords. |
