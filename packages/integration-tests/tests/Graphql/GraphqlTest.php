<?php
namespace Apie\Tests\IntegrationTests\Graphql;

use Apie\IntegrationTests\Graphql\GraphqlProvider;
use Apie\IntegrationTests\Graphql\GraphqlTestHelper;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\PhpunitMatrixDataProvider\MakeDataProviderMatrix;
use Generator;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class GraphqlTest extends TestCase
{
    use MakeDataProviderMatrix;

    public static function it_can_do_a_graphql_call_provider(): Generator
    {
        yield from self::createDataProviderFrom(
            new ReflectionMethod(__CLASS__, 'it_can_do_a_graphql_call'),
            new GraphqlTestHelper()
        );
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('it_can_do_a_graphql_call_provider')]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_do_a_graphql_call(
        TestApplicationInterface $testApplication,
        GraphqlProvider $graphqlProvider
    ) {
        $testApplication->itRunsApplications(function () use ($testApplication, $graphqlProvider){
            $graphqlProvider->bootstrap($testApplication);
            $response = $testApplication->httpRequest(
                $graphqlProvider
            );
            $graphqlProvider->verifyValidResponse($response);
        });
    }
}