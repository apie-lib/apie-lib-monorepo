<?php


namespace Apie\Tests\DateTimePlugin;

use Apie\Core\Apie;
use Apie\DateTimePlugin\DateTimePlugin;
use DateTime;
use PHPUnit\Framework\TestCase;

class DateTimePluginTest extends TestCase
{
    /**
     * @var Apie
     */
    private $apie;

    protected function setUp(): void
    {
        $this->apie = new Apie([new DateTimePlugin()], true, null);
    }

    public function test_serializer_works_with_datetime()
    {
        $serializer = $this->apie->getResourceSerializer();
        $actual = $serializer->normalize(
            new DateTime('@0'),
            'application/json'
        );
        $this->assertEquals('1970-01-01 00:00:00', $actual);
    }

    public function test_schema_is_correct()
    {
        $schemaGenerator = $this->apie->getSchemaGenerator();

        $actual = $schemaGenerator->createSchema(DateTime::class, 'get', ['get', 'read']);
        $this->assertEquals(new Schema(['type' => 'string', 'format' => 'date-time']), $actual);
    }
}
