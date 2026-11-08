# Auditable Objects in Apie

Apie provides a built-in auditing mechanism that allows you to track changes, reads, and method calls on your domain objects.

## Enabling Auditing

To make an entity auditable, simply add the `#[Auditable]` attribute to the class definition.

```php
use Apie\Core\Attributes\Auditable;

#[Auditable]
class MyEntity implements EntityInterface
{
    // ...
}
```

By default, this will audit:
- Resource Creation
- Resource Modification (including which fields changed)
- Resource Removal
- Custom Method Calls

## Configuration Options

The `#[Auditable]` attribute accepts several parameters to fine-tune auditing behavior:

### 1. Auditing Read Events
By default, "Read" events (GET requests) are NOT audited to avoid bloating the audit log. You can enable them using `readEvents` and `readAllEvents`.

- **`readEvents: true`**: Audits when a single resource is retrieved (e.g., `GET /my-entity/123`).
- **`readAllEvents: true`**: Audits when a list of resources is retrieved (e.g., `GET /my-entity`).

```php
#[Auditable(readEvents: true, readAllEvents: false)]
class SensitiveEntity implements EntityInterface
{
}
```

### 2. Permissions
You can restrict who is allowed to view audit logs for a specific entity using the `permission` parameter.

```php
use Apie\Core\Attributes\HasRole;
use Apie\Core\Attributes\RuntimeCheck;

#[Auditable(permission: new RuntimeCheck(new HasRole('ROLE_ADMIN')))]
class AdminOnlyEntity implements EntityInterface
{
}
```

## How it Works

### Storage
Audit logs are stored as `AuditLog` entities in your configured data layer. When at least one entity in a Bounded Context is marked as `#[Auditable]`, the `AuditLog` entity is automatically registered in that Bounded Context.

### Automatic Tracking
The `Apie\Common\Events\AddAuditLog` event subscriber listens to internal Apie events and creates `AuditLog` entries. It automatically captures:
- The **Resource** being acted upon.
- A **Snapshot** of the resource state.
- The **Event Type** (Created, Modified, Read, MethodCalled, etc.).
- The **User** who performed the action (captured from `ApieContext`).
- The **Timestamp**.

### Accessing Audit Logs
Since `AuditLog` is an entity, you can query it through the standard Apie REST or GraphQL APIs. Note that audit logs use `IdFriendlyEntityReference` for the `reference` field, which uses the format `bounded-context-id_EntityName_id`.

- **GraphQL**: `query { findAuditLog(filter: { reference: "my-context_MyEntity_123" }) { totalCount, results { id, event, createdBy, description } } }`
- **REST**: `GET /audit-log?filter[reference]=my-context_MyEntity_123`

## Permissions for Audit Logs
The `permission` parameter in the `#[Auditable]` attribute allows you to restrict who is allowed to view the audit logs for that specific entity. This is in addition to the default behavior where the permission required to view an `AuditLog` entry is the same as the permission required to view the audited entity itself.

This ensures that users cannot bypass security by reading audit logs of resources they aren't allowed to see. If the entity implements `RequiresPermissionsInterface`, those permissions are captured in a snapshot and enforced on the `AuditLog` entity as well.
