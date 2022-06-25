<?php
namespace Apie\Fixtures\TestHelpers;

use Apie\Faker\ApieObjectFaker;
use Faker\Factory;
use Faker\Generator;

trait TestWithFaker
{
    public function runFakerTest(string $classToTest, ?callable $testCase = null, ?Generator $generator = null)
    {
        if (!class_exists(ApieObjectFaker::class)) {
            $this->markTestIncomplete('Faker library not loaded, so skipping test');
            return;
        }
        $testCase ??= function () {
        };
        if (!$generator) {
            $generator = Factory::create();
            $generator->addProvider(ApieObjectFaker::createWithDefaultFakers($generator));
        }
        for ($i = 0; $i < 1000; $i++) {
            $result = $generator->fakeClass($classToTest);
            $this->assertInstanceOf($classToTest, $result);
            $testCase($result, $generator);
        }
    }
}
