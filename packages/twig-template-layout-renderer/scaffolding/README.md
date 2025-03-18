# apie-skeleton-layout

Apie skeleton layout.

## Included:
- all custom elements from [apie-form-elements](https://github.com/apie-lib/apie-form-elements)
- <apie-skeleton-form></apie-skeleton-form> for rendering forms with this layout.

## Usage:

Basically this layout is a wrapper around apie-form-elements and provides a renderer for rendering
form elements.

```html
<apie-form-definition id="example">
    <apie-form-field-definition name="username" types="password" label="Username"></apie-form-field-definition>
    <apie-form-field-definition name="password" types="password" label="Password"></apie-form-field-definition>
</apie-form-definition>
<apie-skeleton-form action="/form-submit" definition-id="example"></apie-skeleton-form>
```
