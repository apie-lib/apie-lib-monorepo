<?php
namespace Apie\TypescriptCodeBuilder\Lists;

use Apie\Core\Attributes\FakeMethod;
use Apie\Core\Lists\ItemHashmap;
use Apie\TypescriptCodeBuilder\Dto\Expressions\StringLiteralExpression;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;
use Faker\Generator;

#[FakeMethod('createRandom')]
class TypescriptExpressionHashmap extends ItemHashmap
{
    public function offsetGet(mixed $offset): TypescriptFileExpressionInterface
    {
        return parent::offsetGet($offset);
    }

    public static function createRandom(Generator $faker): self
    {
        $elm = $faker->randomNumber(1, 4);
        $list = [];
        for ($i = 0; $i < $elm; $i++) {
            $list[$faker->word()] = new StringLiteralExpression($faker->word());
        }
        return new self($list);
    }
}
