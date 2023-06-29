<?php
namespace Apie\Tests\LaravelApie;

use Apie\Common\ApieFacade;
use Apie\LaravelApie\ApieServiceProvider;
use Orchestra\Testbench\TestCase;

class LaravelApieTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ApieServiceProvider::class];
    }

    /**
     * @test
     */
    public function it_can_register_apie_as_a_service()
    {
        $this->assertInstanceOf(ApieFacade::class, resolve('apie'));
    }
}
