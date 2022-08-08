<?php
namespace Apie\Tests\Core\Repositories;

use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Repositories\InMemory\InMemoryRepository;
use Apie\Fixtures\Entities\UserWithAutoincrementKey;
use Apie\Fixtures\ValueObjects\AddressWithZipcodeCheck;
use Apie\TextValueObjects\DatabaseText;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class InMemoryRepositoryTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_remember_entitites()
    {
        $testItem = new InMemoryRepository(new BoundedContextId('default'));
        $this->assertEquals([], $testItem->all(new ReflectionClass(UserWithAutoincrementKey::class))->take(0, 100));
        $user = new UserWithAutoincrementKey(
            new AddressWithZipcodeCheck(
                new DatabaseText('street'),
                new DatabaseText('42'),
                new DatabaseText('1341'),
                new DatabaseText('Amsterdam')
            )
        );
        $testItem->persistNew($user);
        $this->assertEquals([$user], $testItem->all(new ReflectionClass(UserWithAutoincrementKey::class))->take(0, 100));
    }
}
