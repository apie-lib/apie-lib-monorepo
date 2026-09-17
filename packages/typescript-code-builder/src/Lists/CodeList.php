<?php
namespace Apie\TypescriptCodeBuilder\Lists;

use Apie\Core\Attributes\FakeMethod;
use Apie\Core\Lists\ItemList;
use Apie\TypescriptCodeBuilder\Dto\TypescriptDeclaration;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;
use Faker\Generator;

#[FakeMethod('createRandom')]
class CodeList extends ItemList
{
    public function offsetGet(mixed $offset): TypescriptFileExpressionInterface
    {
        return parent::offsetGet($offset);
    }

    public static function createRandom(Generator $faker): self
    {
        $items = [];
        $count = $faker->numberBetween(0, 6);
        for ($i = 0; $i < $count; $i++) {
            $items[] = $faker->fakeClass(TypescriptDeclaration::class);
        }
        return new self($items);
    }
}
