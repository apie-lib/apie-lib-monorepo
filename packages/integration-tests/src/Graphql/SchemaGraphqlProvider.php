<?php
namespace Apie\IntegrationTests\Graphql;

use Apie\Common\IntegrationTestLogger;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class SchemaGraphqlProvider extends GraphqlProvider
{
    private string $key;
    public function bootstrap(TestApplicationInterface $testApplication): void
    {
        $config = $testApplication->getApplicationConfig();
        $this->key = get_class($testApplication) . ',' . $config->getDatalayerImplementation()->name;
        parent::bootstrap($testApplication);
    }

    public function verifyValidResponse(ResponseInterface $response): void
    {
        $body = (string) $response->getBody();
        $statusCode = $response->getStatusCode();
        if ($statusCode === 500) {
            IntegrationTestLogger::failTestShowError();
        }
        TestCase::assertEquals(200, $statusCode, 'Expect object created, got: ' . $body);
        if (IntegrationTestLogger::getLoggedException()) {
            IntegrationTestLogger::failTestShowError($statusCode);
        }
        $data = json_decode($body, true);
        if (isset($data['errors'])) {
            TestCase::fail('GraphQL errors: ' . json_encode($data['errors'], JSON_PRETTY_PRINT));
        }
        $this->expectedResponse = $data;
        $fixtureFile = __DIR__ . '/../../fixtures/Graphql/Schemas/' . md5($this->key . json_encode($this->graphQlQuery)) . 'Schema.json';
        file_put_contents($fixtureFile, json_encode($data, JSON_PRETTY_PRINT));
        parent::verifyValidResponse($response);
    }
}
