<img src="https://raw.githubusercontent.com/apie-lib/apie-lib-monorepo/main/docs/apie-logo.svg" width="100px" align="left" />
<h1>integration-tests</h1>






 [![Latest Stable Version](https://poser.pugx.org/apie/integration-tests/v)](https://packagist.org/packages/apie/integration-tests) [![Total Downloads](https://poser.pugx.org/apie/integration-tests/downloads)](https://packagist.org/packages/apie/integration-tests) [![Latest Unstable Version](https://poser.pugx.org/apie/integration-tests/v/unstable)](https://packagist.org/packages/apie/integration-tests) [![License](https://poser.pugx.org/apie/integration-tests/license)](https://packagist.org/packages/apie/integration-tests) [![PHP Composer](https://apie-lib.github.io/projectCoverage/coverage-integration-tests.svg)](https://apie-lib.github.io/projectCoverage/integration-tests/index.html)  

[![PHP Composer](https://github.com/apie-lib/integration-tests/actions/workflows/php.yml/badge.svg?event=push)](https://github.com/apie-lib/integration-tests/actions/workflows/php.yml)

This package is part of the [Apie](https://github.com/apie-lib) library.
The code is maintained in a monorepo, so PR's need to be sent to the [monorepo](https://github.com/apie-lib/apie-lib-monorepo/pulls)

## Documentation
Matrix-based integration test toolkit used by the other Apie packages to run the same
test scenario against every supported framework (Symfony, Laravel) and datalayer
(in-memory, Doctrine, Faker) combination. It is a development-only dependency, not a
runtime library.

```bash
composer require --dev apie/integration-tests
```

### Key building blocks
- `Apie\IntegrationTests\Interfaces\TestApplicationInterface`: boots a test application
  kernel and exposes its service container and console application. Implementations
  include a Symfony test application and a Laravel one built with Orchestra Testbench
  and `ApieServiceProvider`.
- `Apie\IntegrationTests\Requests\TestRequestInterface`: describes one HTTP request to
  send and how to verify its response. Ready-made requests include
  `GetResourceApiCall`, `ValidCreateResourceApiCall`, `RemoveResourceApiCall`, and
  `ActionMethodApiCall`.
- `Apie\IntegrationTests\IntegrationTestHelper`: combines the `CreatesApplications` and
  `CreatesApieBoundedContext` traits to provide `create...Application()` and
  `create...Request()` factory methods.
- `MakeDataProviderMatrix` (from `apie/phpunit-matrix-data-provider`): a PHPUnit trait
  that turns every application/request factory pair on a helper class into one data
  provider entry.

### Using it in a package's test suite
A package that needs cross-framework coverage adds this package as a dev dependency,
writes a `TestCase` that `use`s `MakeDataProviderMatrix`, and builds its data provider
with `createDataProviderFrom()`:

```php
use Apie\IntegrationTests\IntegrationTestHelper;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\IntegrationTests\Requests\TestRequestInterface;
use Apie\PhpunitMatrixDataProvider\MakeDataProviderMatrix;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

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
    public function it_works(TestApplicationInterface $testApplication, TestRequestInterface $testRequest): void
    {
        $testApplication->bootApplication();
        $response = $testApplication->httpRequest($testRequest);
        $testRequest->verifyValidResponse($response);
        $testApplication->cleanApplication();
    }
}
```

`createDataProviderFrom` inspects `IntegrationTestHelper` (or your own helper class)
for every `create...Application()` and `create...Request()` method and yields the full
cross product, so `it_works` runs once per framework/datalayer/request combination. See
`ai/integration-tests.md` at the repository root for a complete walkthrough.
