<?php
namespace Apie\Tests\IntegrationTests\Console;

use Apie\IntegrationTests\Applications\Symfony\SymfonyTestApplication;
use Apie\IntegrationTests\IntegrationTestHelper;
use Apie\PhpunitMatrixDataProvider\MakeDataProviderMatrix;
use Illuminate\Console\Command;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Symfony\Component\Console\Tester\ApplicationTester;

class SymfonyCommandTest extends TestCase
{
    private const EXPECTED_ROUTES = [
        'external MCP setting (POST)' => 'POST  /mcp',
        'external MCP setting (GET)'  => 'GET  /mcp',
        'WebDAV'                      => 'ANY  /webdav/{path}',
        'javascript code generated'   => 'GET  /js/Apie.es6.js',
        'OpenAPI spec yaml'           => 'GET  /api/types/openapi.yaml',
        'OpenAPI spec JSON'           => 'GET  /api/types/openapi.json',
        'Delete endpoint'             => 'DELETE  /api/types/Order/{id}',
        'Background process method'   => 'POST  /api/types/SequentialBackgroundProcess/{id}/runStep',
    ];

    use MakeDataProviderMatrix;

    #[RunInSeparateProcess]
    #[Test]
    #[DataProvider('it_can_list_all_routes_with_the_regular_symfony_command_provider')]
    public function it_can_list_all_routes_with_the_regular_symfony_command(SymfonyTestApplication $testApplication)
    {
        $testApplication->bootApplication();
        $tester = new ApplicationTester($testApplication->getConsoleApplication());
        $exitCode = $tester->run([
            'debug:route',
        ]);
        $this->assertEquals(Command::SUCCESS, $exitCode, 'console command gave me ' . $tester->getDisplay());
        $output = $tester->getDisplay();
        foreach (self::EXPECTED_ROUTES as $message => $expectedRoute) {
            $pattern = '#' . str_replace('  ', '\s*', $expectedRoute) . '#';
            $this->assertMatchesRegularExpression($pattern, $output, 'Route should exist: ' . $message);
        }
        
        $testApplication->cleanApplication();
    }

    public static function it_can_list_all_routes_with_the_regular_symfony_command_provider(): \Generator
    {
        yield from self::createDataProviderFrom(
            new ReflectionMethod(__CLASS__, 'it_can_list_all_routes_with_the_regular_symfony_command'),
            new IntegrationTestHelper()
        );
    }

    #[RunInSeparateProcess]
    #[Test]
    #[DataProvider('it_can_list_all_routes_with_the_regular_symfony_command_provider')]
    public function it_can_match_all_webdav_routes(SymfonyTestApplication $testApplication)
    {
        $testApplication->bootApplication();
        $tester = new ApplicationTester($testApplication->getConsoleApplication());
        $exitCode = $tester->run([
            'router:match',
            'path_info' => '/webdav',
            '--method' => 'PUT',
            '--scheme' => 'http',
            '--host' => 'localhost',
        ]);
        $this->assertEquals(Command::SUCCESS, $exitCode, 'console command gave me ' . $tester->getDisplay());
        $output = $tester->getDisplay();
        $this->assertStringContainsString('apie._global.apie_webdav', $output, 'Route should match this route name');
        
        $testApplication->cleanApplication();
    }
}
