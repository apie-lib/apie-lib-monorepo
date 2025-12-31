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