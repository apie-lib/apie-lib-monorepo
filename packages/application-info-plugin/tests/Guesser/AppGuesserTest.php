<?php
namespace Apie\Tests\ApplicationInfoPlugin\Guesser;

use Apie\ApplicationInfoPlugin\Guesser\AppGuesser;
use Apie\Core\Apie;
use PHPUnit\Framework\TestCase;

class AppGuesserTest extends TestCase
{
    public function test_it_works()
    {
        $this->assertNotEmpty(AppGuesser::determineHash());
        $this->assertEquals('apie/application-info-plugin ' . Apie::VERSION, AppGuesser::determineApp());
        $this->assertEquals('dev', AppGuesser::determineEnvironment(true));
    }
}
