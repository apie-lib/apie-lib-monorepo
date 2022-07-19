# Enums
Enums are natively supported in PHP and Apie supports them natively. We support both backed and non-backed enums.
In general the enum value is used unless there is no value in which case we communicate the key.

## Context restrictions
The context restriction attributes are fully supported on enum values. Putting them on a value can be used to restrict
setting the enum from an API call

```php
use Apie\Core\Attributes\Requires;

enum TopicStatus: string {
    case DRAFT = 'draft';
    #[Requires('authenticated')]
    case ACCEPTED = 'accepted';
    #[Requires('authenticated')]
    case REJECTED = 'rejected';
}
```

If a user is not authenticated only draft status is allowed in the above example.