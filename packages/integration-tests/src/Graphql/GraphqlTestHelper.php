<?php
namespace Apie\IntegrationTests\Graphql;

use Apie\Core\BoundedContext\BoundedContextId;
use Apie\IntegrationTests\IntegrationTestHelper;

class GraphqlTestHelper extends IntegrationTestHelper
{
    public function createEmptyCall(): GraphqlProvider
    {
        return new GraphqlProvider(
            new BoundedContextId('types'),
            [
                'query' => '{ apie_version }'
            ],
            [
                "data" => [
                    "apie_version" => \Apie\Core\ApieLib::VERSION,
                ],
            ]
        );
    }

    public function createSchemaCall(): GraphqlProvider
    {
        return new SchemaGraphqlProvider(
            new BoundedContextId('types'),
            [
                'query' => '{
  __schema {
    queryType {
      fields {
        name
        description
      }
    }
  }
}'
            ],
            [
            ]
        );
    }

    public function createTypeInspectionCall(): GraphqlProvider
    {
        return new SchemaGraphqlProvider(
            new BoundedContextId('types'),
            [
                'query' => 'query IntrospectionQuery {
  __schema {
    queryType {
      name
    }
    mutationType {
      name
    }
    subscriptionType {
      name
    }
    types {
      ...FullType
    }
    directives {
      name
      description
      locations
      args {
        ...InputValue
      }
    }
  }
}

fragment FullType on __Type {
  kind
  name
  description
  fields(includeDeprecated: true) {
    name
    description
    args {
      ...InputValue
    }
    type {
      ...TypeRef
    }
    isDeprecated
    deprecationReason
  }
  inputFields {
    ...InputValue
  }
  interfaces {
    ...TypeRef
  }
  enumValues(includeDeprecated: true) {
    name
    description
    isDeprecated
    deprecationReason
  }
  possibleTypes {
    ...TypeRef
  }
}

fragment InputValue on __InputValue {
  name
  description
  type {
    ...TypeRef
  }
  defaultValue
}

fragment TypeRef on __Type {
  kind
  name
  ofType {
    kind
    name
    ofType {
      kind
      name
      ofType {
        kind
        name
        ofType {
          kind
          name
          ofType {
            kind
            name
            ofType {
              kind
              name
              ofType {
                kind
                name
              }
            }
          }
        }
      }
    }
  }
}
'
            ],
            []
        );
    }

    public function createIntrospectionCall(): GraphqlProvider
    {
        return new SchemaGraphqlProvider(
            new BoundedContextId('types'),
            [
                'query' => '{
  __type(name: "findPrimitiveOnly") {
    name
    description
    fields {
      name
      description
    }
  }
}'
            ],
            [
            ]
        );
    }

}
