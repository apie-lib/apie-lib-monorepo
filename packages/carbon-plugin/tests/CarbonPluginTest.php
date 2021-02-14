<?php


namespace Apie\Tests\CarbonPlugin;

use Apie\CarbonPlugin\CarbonPlugin;
use Apie\Core\Apie;
use Apie\OpenapiSchema\Spec\Schema;
use Carbon\Carbon;
use DateTimeZone;
use PHPUnit\Framework\TestCase;

class CarbonPluginTest extends TestCase
{
    /**
     * @var Apie
     */
    private $apie;

    protected function setUp(): void
    {
        $this->apie = new Apie([new CarbonPlugin()], true, null);
    }

    public function test_serializer_works_with_carbon()
    {
        $serializer = $this->apie->getResourceSerializer();
        $actual = $serializer->normalize(
            Carbon::createFromTimestamp(0, new DateTimeZone('Europe/Amsterdam')),
            'application/json'
        );
        $this->assertEquals('1970-01-01 01:00:00', $actual);
    }

    public function test_schema_is_correct()
    {
        $schemaGenerator = $this->apie->getSchemaGenerator();

        $actual = $schemaGenerator->createSchema(Carbon::class, 'get', ['get', 'read']);
        $this->assertEquals(Schema::createFromInternal(['type' => 'string', 'format' => 'date-time']), $actual);
    }
}
