<?php
namespace Apie\TypescriptCodeBuilder\Lists;

use Apie\Core\Attributes\FakeMethod;
use Apie\Core\Lists\ItemList;
use Apie\TypescriptCodeBuilder\Dto\IIFE;
use Apie\TypescriptCodeBuilder\Dto\ImportStatement;
use Apie\TypescriptCodeBuilder\Dto\RawJavascript;
use Apie\TypescriptCodeBuilder\Dto\TypescriptDeclaration;
use Apie\TypescriptCodeBuilder\Dto\VariableAssignment;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;
use Apie\TypescriptCodeBuilder\TypescriptTypeDeclarationInterface;
use Faker\Generator;
use LogicException;

#[FakeMethod('createRandom')]
class CodeList extends ItemList
{
    public function sort(): self
    {
        $items = $this->toArray();
        $providedDefinitions = [];
        foreach ($items as $item) {
            $providedDefinitions = array_merge(
                $providedDefinitions,
                $item->providesDefinitions(false)->toStringArray()
            );
        }

        $sortedItems = [];
        $availableDefinitions = [];
        while ($items !== []) {
            $progress = false;
            foreach ($items as $index => $item) {
                $neededDefinitions = array_intersect(
                    $item->needsDefinitions()->toStringArray(),
                    $providedDefinitions
                );
                if (array_diff($neededDefinitions, $availableDefinitions) !== []) {
                    continue;
                }

                $sortedItems[] = $item;
                $availableDefinitions = array_merge(
                    $availableDefinitions,
                    $item->providesDefinitions(false)->toStringArray()
                );
                unset($items[$index]);
                $progress = true;
            }
            if (!$progress) {
                throw new LogicException('Unable to sort code list because it contains a circular dependency.');
            }
        }

        return new self($sortedItems);
    }

    public function makeImportsInline(string $tsImportPath, string $jsImportPath): self
    {
        $mappedItems = [];
        foreach ($this as $item) {
            if ($item instanceof ImportStatement) {
                // TODO: typescript imports will be built in the future
                if ($item->typeOnly) {
                    continue;
                }
                $fullTsPath = $tsImportPath . '/' . $item->source . '.ts';
                $fullJsPath = $jsImportPath . '/' . $item->source . '.js';
                if (!file_exists($fullTsPath)) {
                    $fullTsPath = $fullJsPath;
                }
                $mappedItems[] = new IIFE(new CodeList([
                    new RawJavascript(
                        file_get_contents($fullJsPath) ?: '',
                        file_get_contents($fullTsPath) ?: '',
                    )
                ]));
            } else {
                $mappedItems[] = $item;
            }
        }
        return new self($mappedItems);
    }

    public function offsetGet(mixed $offset): TypescriptFileExpressionInterface|TypescriptTypeDeclarationInterface
    {
        return parent::offsetGet($offset);
    }

    public static function createRandom(Generator $faker): self
    {
        $items = [];
        $count = $faker->numberBetween(0, 6);
        for ($i = 0; $i < $count; $i++) {
            $items[] = $faker->fakeClass($faker->randomElement([TypescriptDeclaration::class, VariableAssignment::class]));
        }
        return new self($items);
    }
}
