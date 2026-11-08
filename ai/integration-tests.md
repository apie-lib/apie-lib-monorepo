# Integration Testing in Apie

Apie uses a matrix-based integration testing system to ensure that features work identically across all supported frameworks (Symfony and Laravel) and various data layer implementations (In-memory, Doctrine, Faker).

## Core Concepts

### 1. Matrix Testing
The goal is to run the same test suite against different application kernels. This ensures framework-agnostic behavior and prevents regressions in specific adapters.

### 2. Key Interfaces
- **`TestApplicationInterface`**: Abstrates the application kernel (booting, service container access, HTTP request handling).
    - `SymfonyTestApplication`: Uses `SymfonyTestingKernel`.
    - `LaravelTestApplication`: Uses Orchestra Testbench to boot a Laravel application with `ApieServiceProvider`.
- **`TestRequestInterface`**: Defines an HTTP request to be executed and provides methods to verify the response.
    - Implementations include `ActionMethodApiCall`, `GetResourceApiCall`, `ValidCreateResourceApiCall`, etc.

### 3. Matrix Generation
- **`IntegrationTestHelper`**: A central class (using traits like `CreatesApplications` and `CreatesApieBoundedContext`) that provides factory methods for applications and requests.
- **`MakeDataProviderMatrix`**: A PHPUnit trait. When used in a test class, it allows generating a test matrix.
    - It looks for `create...Application` and `create...Request` methods in a provided helper class.
    - It yields every possible combination of application and request to the test method.

## How to Write an Integration Test

### Step 1: Create a Test Class
Inherit from `PHPUnit\Framework\TestCase` and use `MakeDataProviderMatrix`.

```php
class MyFeatureTest extends TestCase
{
    use MakeDataProviderMatrix;

    public static function it_works_provider(): Generator
    {
        yield from self::createDataProviderFrom(
            new ReflectionMethod(__CLASS__, 'it_works'),
            new IntegrationTestHelper()
        );
    }

    /**
     * @dataProvider it_works_provider
     */
    public function it_works(TestApplicationInterface $testApplication, TestRequestInterface $testRequest)
    {
        $testApplication->bootApplication();
        
        $response = $testApplication->httpRequest($testRequest);
        
        $testRequest->verifyValidResponse($response);
        
        $testApplication->cleanApplication();
    }
}
```

### Step 2: Define Requests
Requests are usually defined in `Apie\IntegrationTests\Concerns\CreatesApieBoundedContext`. Add a new `create...Request` method there if your test needs a specific scenario.

```php
public function createMyNewFeatureRequest(): TestRequestInterface
{
    return new ActionMethodApiCall(
        new BoundedContextId('my-context'),
        'my-endpoint',
        new GetPrimitiveField('', 'expected-value')
    );
}
```

### Step 3: Running Tests
Run integration tests for the `integration-tests` package:
```bash
bin/run-package-test integration-tests
```

## Best Practices
- **Framework Independence**: Avoid writing tests that only work on one framework unless specifically testing a framework-specific feature.
- **OpenAPI Validation**: Use `validateOpenApiSpec` (found in `DoApiCallTest`) to ensure the generated documentation matches the actual API behavior.
- **State Management**: Always call `bootApplication()` at the start and `cleanApplication()` at the end of each test.
- **Datalayers**: The matrix will automatically test your request against all registered Datalayer implementations. Ensure your domain objects are compatible with them (e.g., Doctrine requires specific mapping if not using Apie's auto-mapping).
