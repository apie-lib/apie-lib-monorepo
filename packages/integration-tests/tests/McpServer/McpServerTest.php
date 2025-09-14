<?php
namespace Apie\Tests\IntegrationTests\McpServer;

use Apie\Common\IntegrationTestLogger;
use Apie\IntegrationTests\IntegrationTestHelper;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\IntegrationTests\Requests\TestRequestInterface;
use Apie\PhpunitMatrixDataProvider\MakeDataProviderMatrix;
use Generator;
use Mcp\Server\Transport\Http\HttpSession;
use Nyholm\Psr7\ServerRequest;
use Nyholm\Psr7\Stream;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionMethod;

class McpServerTest extends TestCase
{
    use MakeDataProviderMatrix;

    public static function it_can_run_mcp_server_provider(): Generator
    {
        yield from self::createDataProviderFrom(
            new ReflectionMethod(__CLASS__, 'it_can_run_mcp_server'),
            new IntegrationTestHelper()
        );
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('it_can_run_mcp_server_provider')]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_run_mcp_server(TestApplicationInterface $testApplication)
    {
        $testApplication->bootApplication();
        
        $sessionStore = $testApplication->getServiceContainer()->get('apie.mcp_store');

        $session = new HttpSession();
        $sessionId = $session->getId();
        $sessionStore->save($session);

        try {
            $request = new class($sessionId) implements TestRequestInterface {
                private $sessionId;

                public function __construct(string $sessionId)
                {
                    $this->sessionId = $sessionId;
                }

                public function getRequest(): ServerRequestInterface
                {
                    $jsonRpcPayload = [
                        [
                            'jsonrpc' => '2.0',
                            'method'  => 'initialize',
                            'id'      => 1,
                            'params' => [
                                'clientInfo' => [
                                    'name' => 'Integration test',
                                    'version' => '0.1.0',
                                ],
                                'protocolVersion' => '1.0.0',
                            ]
                        ],
                        [
                            'jsonrpc' => '2.0',
                            'method'  => 'tools/list',
                            'id'      => 2
                        ]
                    ];

                    $body = Stream::create(json_encode($jsonRpcPayload));

                    return new ServerRequest(
                        'POST',
                        '/mcp',
                        [
                            'Host' => 'localhost',
                            'Content-Type' => 'application/json',
                            'Mcp-Session-Id' => $this->sessionId,
                        ],
                        $body
                    );
                }

                public function verifyValidResponse(ResponseInterface $response): void
                {
                    $statusCode = $response->getStatusCode();
                    if ($statusCode === 500) {
                        IntegrationTestLogger::failTestShowError();
                    }
                    $responseBody = (string) $response->getBody();
                    TestCase::assertEquals(200, $statusCode, 'Expect status code 200, got: ' . $responseBody);
                    TestCase::assertEquals('application/json', $response->getHeaderLine('Content-Type'));
                    
                    $decodedResponse = json_decode($responseBody, true);
                    TestCase::assertIsArray($decodedResponse, 'Response is ' . $responseBody  . ' ' . json_last_error_msg());
                    TestCase::assertCount(2, $decodedResponse, 'Expected 2 keys in batch, got: ' . $responseBody);
                    
                    // Check each response
                    foreach ($decodedResponse as $index => $result) {
                        if ($index === 1) {
                            TestCase::assertIsArray($result['result']);
                        }
                        TestCase::assertEquals('2.0', $result['jsonrpc']);
                        TestCase::assertArrayHasKey('id', $result);
                    }
                }

                public function shouldDoRequestValidation(): bool
                {
                    return false;
                }

                public function shouldDoResponseValidation(): bool
                {
                    return true;
                }
            };

            $response = $testApplication->httpRequest($request);
            $request->verifyValidResponse($response);
        } finally {
            $sessionStore->delete($sessionId);
            $testApplication->cleanApplication();
        }
    }
}
