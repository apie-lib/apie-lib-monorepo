<?php
namespace Apie\Tests\RestApi\Controllers;

use Apie\Core\BoundedContext\BoundedContext;
use Apie\Core\BoundedContext\BoundedContextHashmap;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\ContextBuilders\ContextBuilderFactory;
use Apie\Core\Lists\ReflectionClassList;
use Apie\Core\Lists\ReflectionMethodList;
use Apie\Core\RouteDefinitions\ActionHashmap;
use Apie\Core\RouteDefinitions\RouteDefinitionsProviderList;
use Apie\Fixtures\Actions\StaticActionExample;
use Apie\RestApi\ActionProvider;
use Apie\RestApi\Controllers\RestApiController;
use Apie\RestApi\Interfaces\RestApiRouteDefinition;
use Apie\RestApi\RouteDefinitions\RunGlobalMethodRouteDefinition;
use Apie\Serializer\DecoderHashmap;
use Apie\Serializer\EncoderHashmap;
use Apie\Serializer\Serializer;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionMethod;

class RestApiControllerTest extends TestCase
{
    protected function givenAControllerToRunArbitraryMethod(): RestApiController
    {
        $boundedContext = $this->givenABoundedContext();
        $boundedContextHashmap = new BoundedContextHashmap(['test' => $boundedContext]);
        return new RestApiController(
            ContextBuilderFactory::create(),
            $boundedContextHashmap,
            new ActionProvider(
                new RouteDefinitionsProviderList(
                    new ActionHashmap(
                        [
                            '/SecretCode' => new RunGlobalMethodRouteDefinition(
                                new ReflectionMethod(StaticActionExample::class, 'secretCode'),
                                $boundedContext->getId()
                            ),
                        ]
                    )
                ),
                $boundedContextHashmap,
                Serializer::create()
            ),
            EncoderHashmap::create(),
            DecoderHashmap::create()
        );
    }

    protected function givenABoundedContext(): BoundedContext
    {
        return new BoundedContext(
            new BoundedContextId('test'),
            new ReflectionClassList([
            ]),
            new ReflectionMethodList([
                new ReflectionMethod(StaticActionExample::class, 'secretCode')
            ])
        );
    }

    protected function givenAGetRequest(string $uri): ServerRequestInterface
    {
        $factory = new Psr17Factory();
        return $factory->createServerRequest('GET', $uri)
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Accept', 'application/json')
            ->withAttribute(RestApiRouteDefinition::BOUNDED_CONTEXT_ID, 'test')
            ->withAttribute(RestApiRouteDefinition::SERVICE_CLASS, StaticActionExample::class)
            ->withAttribute(RestApiRouteDefinition::METHOD_NAME, 'secretCode')
            ->withAttribute(RestApiRouteDefinition::OPERATION_ID, 'call-method-StaticActionExample-secretCode');
    }

    /**
     * @test
     */
    public function it_can_run_a_method()
    {
        $testItem = $this->givenAControllerToRunArbitraryMethod();
        $request = $this->givenAGetRequest('/SecretCode');
        $actual = $testItem($request);
        $this->assertStringContainsStringIgnoringCase('application/json', $actual->getHeader('Content-Type')[0] ?? '(null)');
        $body = json_decode((string) $actual->getBody(), true);
        $expectedData = StaticActionExample::secretCode()->toArray();
        $this->assertEquals(
            $expectedData,
            $body
        );
    }
}