<?php
namespace Apie\Tests\Common\Actions;

use Apie\Common\Actions\GetItemAction;
use Apie\Common\Actions\GetListAction;
use Apie\Common\ContextConstants;
use Apie\Common\Tests\Concerns\ProvidesApieFacade;
use Apie\Core\Context\ApieContext;
use Apie\Core\Lists\ItemHashmap;
use Apie\Fixtures\Entities\UserWithAddress;
use Apie\Serializer\Lists\SerializedList;
use PHPUnit\Framework\TestCase;

class GetItemActionTest extends TestCase
{
    use ProvidesApieFacade;

    /** @test */
    public function it_can_display_an_item()
    {
        $testItem = $this->givenAnApieFacade(GetItemAction::class);
        $context = new ApieContext([
            ContextConstants::RESOURCE_NAME => UserWithAddress::class,
            ContextConstants::RESOURCE_ID => 1
        ]);
        /** @var GetListAction $action */
        $action = $testItem->getAction('default', 'test', $context);
        $response = $action(
            $context,
            []
        );

        $this->assertInstanceOf(ItemHashmap::class, $response);
        $this->assertEquals(
            [
                'totalCount' => 0,
                'list' => new SerializedList([]),
                'first' => '/default/UserWithAddress?items_per_page=5',
                'last' => '/default/UserWithAddress?items_per_page=5',
            ],
            $response->toArray()
        );
    }
}
