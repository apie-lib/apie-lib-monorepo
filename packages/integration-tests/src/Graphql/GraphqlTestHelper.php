<?php
namespace Apie\IntegrationTests\Graphql;

use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\FileStorage\ImageFile;
use Apie\Core\FileStorage\StoredFile;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\PrimitiveOnlyIdentifier;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\UploadedFileIdentifier;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\PrimitiveOnly;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\UploadedFile;
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

    /**
     * @return array<int, PrimitiveOnly>
     */
    private function createEntityList(int $count): array
    {
        $entities = [];
        for ($i = 0; $i < $count; $i++) {
            $entity = new PrimitiveOnly(PrimitiveOnlyIdentifier::generateFromInteger($i));
            $entity->stringField = 'String ' . $i;
            $entity->integerField = $i * 10;
            $entity->floatingPoint = $i * 1.5;
            $entity->booleanField = $i % 2 === 0;
            $entities[] = $entity;
        }
        return $entities;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function createExpectedListResponse(int $start, int $end): array
    {
        $list = [];
        for ($i = $start; $i <= $end; $i++) {
            $list[] = [
                'id' => (string) PrimitiveOnlyIdentifier::generateFromInteger($i),
                'stringField' => 'String ' . $i,
                'integerField' => $i * 10,
                'floatingPoint' => $i * 1.5,
                'booleanField' => $i % 2 === 0,
            ];
        }
        return $list;
    }

    public function createQueryCall(): GraphqlProvider
    {
        $entities = $this->createEntityList(30);
        return new GraphqlProvider(
            new BoundedContextId('types'),
            [
                  'query' => '{ findPrimitiveOnly(filter: { orderBy: "+id" }) { totalCount, list { id, stringField, integerField, floatingPoint, booleanField } } }'
              ],
            [
                'data' => [
                    'findPrimitiveOnly' => [
                        'list' => $this->createExpectedListResponse(0, 19),
                        'totalCount' => 30,
                    ]
                ]
              ],
            $entities
        );
    }

    public function createFileQuery(): GraphqlProvider
    {
        $path = __DIR__ . '/../../fixtures/apie-logo.svg';
        $entities = [
          new UploadedFile(
              UploadedFileIdentifier::fromNative('821c5aca-2853-4f7a-b3bc-045a81798e24'),
              StoredFile::createFromLocalFile($path),
              ImageFile::createFromLocalFile($path),
          )
        ];
        return new GraphqlProvider(
            new BoundedContextId('types'),
            [
                  'query' => '{ findUploadedFile(id: "821c5aca-2853-4f7a-b3bc-045a81798e24") { list { id, imageFile, file } } }'
              ],
            [
                'data' => [
                    'findUploadedFile' => [
                        'list' => [
                          [
                            'id' => '821c5aca-2853-4f7a-b3bc-045a81798e24',
                            'imageFile' => '/types/UploadedFile/821c5aca-2853-4f7a-b3bc-045a81798e24/download/imageFile',
                            'file' => '/types/UploadedFile/821c5aca-2853-4f7a-b3bc-045a81798e24/download/file',
                          ]
                        ]
                    ]
                ]
            ],
            $entities
        );
    }

    public function createMutationCall(): GraphqlProvider
    {
        $entities = $this->createEntityList(30);
        return new GraphqlProvider(
            new BoundedContextId('types'),
            [
              'query' => <<<GQL
mutation CreatePrimitiveOnly(\$id: String!, \$booleanField: Boolean!) {
  createPrimitiveOnly(input: {
    id: \$id,
    booleanField: \$booleanField
  }) { id, stringField, integerField, booleanField }
}
GQL,
                'variables' => [
                    'id' => '821c5aca-2853-4f7a-b3bc-045a81798e24',
                    'booleanField' => false,
                ],
            ],
            [
                'data' => [
                    'createPrimitiveOnly' => [
                        'id' => '821c5aca-2853-4f7a-b3bc-045a81798e24',
                        'stringField' => null,
                        'integerField' => null,
                        'booleanField' => false,
                    ]
                ]
              ],
            $entities
        );
    }

    public function createFileuploadCall(): GraphqlProvider
    {
        return new GraphqlWithFileUpload(
            new BoundedContextId('types'),
            [
              'query' => <<<GQL
mutation CreateUploadedFile(\$id: String!, \$file: UploadedFileInterface_create!) {
  createUploadedFile(input: {
    id: \$id,
    file: \$file
  }) { id, file, stream, imageFile }
}
GQL,
                'variables' => [
                    'id' => '821c5aca-2853-4f7a-b3bc-045a81798e24',
                ],
            ],
            [
                'data' => [
                    'createUploadedFile' => [
                        'id' => '821c5aca-2853-4f7a-b3bc-045a81798e24',
                        'imageFile' => null,
                        'stream' => '/types/UploadedFile/821c5aca-2853-4f7a-b3bc-045a81798e24/download/stream',
                        'file' => '/types/UploadedFile/821c5aca-2853-4f7a-b3bc-045a81798e24/download/file',
                    ]
                ]
              ],
            [],
            [
                'variables.file' => StoredFile::createFromString('test', 'test.txt'),
              ]
        );
    }

}
