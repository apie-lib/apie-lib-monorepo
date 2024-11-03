# apie-ionic-form



<!-- Auto Generated Below -->


## Properties

| Property                    | Attribute | Description | Type                                                 | Default     |
| --------------------------- | --------- | ----------- | ---------------------------------------------------- | ----------- |
| `initialValue`              | --        |             | `{ [key: string]: NestedRecordField<SubmitField>; }` | `undefined` |
| `internalState`             | --        |             | `{ [key: string]: NestedRecordField<Primitive>; }`   | `{}`        |
| `polymorphicFormDefinition` | --        |             | `{ [x: string]: string; }`                           | `undefined` |
| `validationErrors`          | --        |             | `{ [key: string]: NestedRecordField<string>; }`      | `{}`        |
| `value`                     | --        |             | `{ [key: string]: NestedRecordField<SubmitField>; }` | `{}`        |


## Dependencies

### Depends on

- apie-form

### Graph
```mermaid
graph TD;
  apie-skeleton-form --> apie-form
  apie-form --> apie-single-input
  apie-form --> apie-form-map
  apie-form --> apie-form-select
  apie-form --> apie-render-types
  style apie-skeleton-form fill:#f9f,stroke:#333,stroke-width:4px
```

----------------------------------------------

*Built with [StencilJS](https://stenciljs.com/)*
