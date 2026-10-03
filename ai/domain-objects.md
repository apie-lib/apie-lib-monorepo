# Apie Domain Object Documentation

Apie follows a "Domain-objects-first" approach. This guide explains how to create proper domain objects using Value Objects, Entities, Enums, and File Uploads.

## Value Objects

Value objects are immutable and represent a primitive value with business logic.

### String Value Objects
Use the `IsStringValueObject` trait for objects that represent a single string.

```php
<?php
namespace App\ValueObjects;

use Apie\Core\ValueObjects\IsStringValueObject;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;

class EmailAddress implements StringValueObjectInterface
{
    use IsStringValueObject;

    public static function validate(string $input): void
    {
        if (!filter_var($input, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Invalid email address: $input");
        }
    }
}
```

### Composite Value Objects
Use the `CompositeValueObject` trait for objects consisting of multiple properties. Avoid "primitive obsession" by using existing value objects for properties instead of raw primitives.

```php
<?php
namespace App\ValueObjects;

use Apie\Core\ValueObjects\CompositeValueObject;
use Apie\Core\ValueObjects\DatabaseText;
use Apie\Core\ValueObjects\Interfaces\ValueObjectInterface;
use Apie\Core\ValueObjects\NonEmptyString;

class ProductDetails implements ValueObjectInterface
{
    use CompositeValueObject;

    private NonEmptyString $sku;
    
    private DatabaseText $description;

    public function getSku(): NonEmptyString
    {
        return $this->sku;
    }

    public function getDescription(): DatabaseText
    {
        return $this->description;
    }
}
```

## Enums

Apie natively supports PHP 8.1+ enums.

```php
<?php
namespace App\Enums;

enum UserStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case PENDING = 'pending';
}
```

## Entities

Entities are mutable objects with an identifier.

### Identifiers
Identifiers are special value objects used to reference entities. They often extend a base identifier class or use a trait.

```php
<?php
namespace App\Identifiers;

use Apie\Core\Identifiers\Uuid;
use Apie\Core\Identifiers\IdentifierInterface;

class UserId extends Uuid implements IdentifierInterface
{
}
```

### Root Aggregates
Root Aggregates are the main entry points for your application. Implement `RootAggregate`.

```php
<?php
namespace App\Entities;

use App\Identifiers\UserId;
use App\ValueObjects\EmailAddress;
use App\Enums\UserStatus;
use Apie\Core\Entities\RootAggregate;

class User implements RootAggregate
{
    private UserStatus $status;

    public function __construct(
        private UserId $id,
        private EmailAddress $email
    ) {
        $this->status = UserStatus::PENDING;
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getEmail(): EmailAddress
    {
        return $this->email;
    }
}
```

## File Uploads

Apie uses `Psr\Http\Message\UploadedFileInterface` to handle file uploads in domain objects. **Important**: You should add the `#[AllowMultipart]` attribute to any entity that supports file uploads to ensure they work correctly in non-JSON (multipart/form-data) setups.

```php
<?php
namespace App\Entities;

use App\Identifiers\ProfilePictureId;
use Apie\Core\Attributes\AllowMultipart;
use Apie\Core\Entities\EntityInterface;
use Psr\Http\Message\UploadedFileInterface;

#[AllowMultipart]
class ProfilePicture implements EntityInterface
{
    public function __construct(
        private ProfilePictureId $id,
        private UploadedFileInterface $file
    ) {
    }

    public function getId(): ProfilePictureId
    {
        return $this->id;
    }

    public function getFile(): UploadedFileInterface
    {
        return $this->file;
    }
}
```

## Lists & Sets

Use Apie's list and set classes for collections.

```php
use Apie\Core\Lists\StringList;
use Apie\Core\Lists\ItemSet;

// A list of strings
$list = new StringList(['item1', 'item2']);

// A set of unique items
$set = new ItemSet([$item1, $item2]);
```
