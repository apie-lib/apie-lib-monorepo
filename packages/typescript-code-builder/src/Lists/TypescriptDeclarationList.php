<?php
namespace Apie\TypescriptCodeBuilder\Lists;

use Apie\Core\Attributes\FakeMethod;
use Apie\Core\Lists\ItemList;
use Apie\TypescriptCodeBuilder\Dto\Typehints\IdentifierTypeDefinition;
use Apie\TypescriptCodeBuilder\Dto\Typehints\LiteralTypeDefinition;
use Apie\TypescriptCodeBuilder\Enums\TypescriptType;
use Apie\TypescriptCodeBuilder\TypescriptTypeDeclarationInterface;
use Faker\Generator;

#[FakeMethod('createRandom')]
class TypescriptDeclarationList extends ItemList
{
    public function offsetGet(mixed $offset): TypescriptTypeDeclarationInterface
    {
        return parent::offsetGet($offset);
    }

    public static function createRandom(Generator $faker): self
    {
        $list = [];
        $count = $faker->numberBetween(0, 3);
        for ($i = 0; $i < $count; $i++) {
            $list[] = $faker->fakeClass(
                $faker->randomElement([IdentifierTypeDefinition::class,TypescriptType::class, LiteralTypeDefinition::class])
            );
        }
        return new TypescriptDeclarationList($list);
    }
}
