<?php
namespace Apie\HtmlBuilders\Columns;

use Apie\Core\Context\ApieContext;
use Apie\Core\Entities\PolymorphicEntityInterface;
use Generator;
use ReflectionClass;

final class ColumnSelector {
    /**
     * @return array<int, string>
     */
    public function getColumns(ReflectionClass $class, ApieContext $context): array
    {
        $columns = $this->getFromSingleClass($class, $context);
        usort($columns, [$this, 'sortCallback']);
        if ($class->implementsInterface(PolymorphicEntityInterface::class)) {
            $discriminatorColumns = $this->getPolymorphicColumns($class);
            $columns = [...array_slice($columns, 0, 1), ...$discriminatorColumns, ...array_slice($columns, 1)];
            $done = [];
            foreach ($this->iterateOverChildClasses($class, $done) as $childClass) {
                $columns = [...$columns, $this->getColumns($childClass, $context)];
            }
            $columns = array_values(array_unique($columns));
        }

        return $columns;
    }

    /**
     * @return Generator<int, DiscriminatorMapping>
     */
    private function iterateOverDiscriminatorMappings(ReflectionClass $class): Generator
    {
        while ($class) {
            $method = $class->getMethod('getDiscriminatorMapping');
            if ($method->getDeclaringClass()->name === $class->name && !$method->isAbstract()) {
                yield $method->invoke(null);
            $class = $class->getParentClass();
        }
    }

    /**
     * @return Generator<int, ReflectionClass<PolymorphicEntityInterface>>
     */
    private function iterateOverChildClasses(ReflectionClass $class, array& $done): Generator
    {
        $method = $class->getMethod('getDiscriminatorMapping');
        $declaredClass = $method->getDeclaringClass()->name;
        if (in_array($declaredClass, $done)) {
            return;
        }
        $done[] = $declaredClass;
        $mapping = $method->invoke(null);
        foreach ($mapping->getConfigs() as $config) {
            $refl = new ReflectionClass($config->getClassName());
            yield $refl;
            yield from $this->iterateOverChildClasses($refl, $done);
        }
    }

    /**
     * @return array<int, string>
     */
    public function getPolymorphicColumns(ReflectionClass $class): array
    {
        $list = [];
        foreach ($this->iterateOverDiscriminatorMappings($class) as $mapping) {
            $list[] = $mapping->getPropertyMapping();
            foreach ($mapping->getConfigs() as $config) {
                array_push($list, ...$this->getPolymorphicColumns(new ReflectionClass($config->getClassName())));
            }
        }
        
        return $list;
    }

    /**
     * @return array<int, string>
     */
    private function getFromSingleClass(ReflectionClass $class, ApieContext $context): array
    {
        $columns = array_keys($context->getApplicableGetters($class)->toArray());

        return $columns;
    }

    private function sortCallback(string $input1, string $input2): int {
            $rating1 = $this->rating($input1);
            $rating2 = $this->rating($input2);
            if ($rating1 === $rating2) {
                return $input1 <=> $input2;
            }
            return $rating1 <=> $rating2;
    }

    private function rating(string $input): int
    {
        if (stripos('status', $input) !== false) {
            return 150;
        }
        $ratings = [
            'id' => 300,
            'name' => 250,
            'email' => 200,
            'description' => -100,
        ];
        return $ratings[$input] ?? 0;
    }
}