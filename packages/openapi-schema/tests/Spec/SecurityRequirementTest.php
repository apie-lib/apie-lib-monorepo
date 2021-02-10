<?php


namespace Apie\Tests\OpenapiSchema\Spec;

use Apie\OpenapiSchema\Spec\SecurityRequirement;
use PHPUnit\Framework\TestCase;

class SecurityRequirementTest extends TestCase
{
    public function testWorksAsIntended()
    {
        $testItem = SecurityRequirement::fromNative([]);
        $this->assertEquals([], $testItem->toNative());

        $testItem = new SecurityRequirement([
            'numbers' => [1, 2, 3],
            'google' => ['count:numbers', 'search:numbers'],
        ]);
        $this->assertEquals(
            [
                'numbers' => ['1', '2', '3'],
                'google' => ['count:numbers', 'search:numbers'],
            ],
            $testItem->toNative()
        );
    }
}
