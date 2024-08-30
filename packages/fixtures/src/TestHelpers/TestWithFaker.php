<?php
namespace Apie\Fixtures\TestHelpers;

use Apie\Core\Utils\EntityUtils;
use Apie\Core\Utils\ValueObjectUtils;
use Apie\Faker\ApieObjectFaker;
use Faker\Factory;
use Faker\Generator;

/** @codeCoverageIgnore */
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
        $interval = 1000;
        if (EntityUtils::isEntity($classToTest) || ValueObjectUtils::isCompositeValueObject($classToTest)) {
            $interval = 100;
        }
        if (preg_match('/File$/', $classToTest)) {
            $interval = 10;
        }
        for ($i = 0; $i < $interval; $i++) {
            $result = $generator->fakeClass($classToTest);
            $this->assertInstanceOf($classToTest, $result);
            $testCase($result, $generator);
        }
    }
}
