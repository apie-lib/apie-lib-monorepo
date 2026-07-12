<?php
namespace Apie\Tests\IntegrationTests;

use Apie\Core\Dto\DtoInterface;
use Apie\Core\Lists\ItemHashmap;
use Apie\Core\Lists\ItemList;
use Apie\Core\Lists\ItemSet;
use Apie\Core\ValueObjects\Interfaces\ValueObjectInterface;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use Spatie\StructureDiscoverer\Discover;
use Spatie\StructureDiscoverer\Data\DiscoveredStructure;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UploadedFileInterface;
use ReflectionClass;
use Spatie\StructureDiscoverer\Support\Conditions\ConditionBuilder;
use Symfony\Component\Finder\Finder;

class ComposerExtraIsCompleteTest extends TestCase
{
    private static function findPath(string $package): string
    {
        return __DIR__ . '/../../' . $package;
    }

    private static function readComposerFor(string $package): array
    {
        return json_decode(file_get_contents(self::findPath($package) . '/composer.json'), true);
    } 

    #[DataProvider('packageProvider')]
    public function testPackageComposer(string $package)
    {
        $composer = self::readComposerFor($package);
        $classes = $composer['extra']["apie-objects"] ?? [];
        $classesFound = Discover::in(self::findPath($package . '/src'))
            ->any(
                ConditionBuilder::create()->exact(
                    ConditionBuilder::create()->classes(),
                    ConditionBuilder::create()->custom(function (DiscoveredStructure $structure) {
                        $interfaces = [
                            UploadedFileInterface::class,
                            ValueObjectInterface::class,
                            DtoInterface::class,
                        ];
                        $refl = (new \ReflectionClass($structure->getFcqn()));
                        if ($refl->isAbstract()) {
                            return false;
                        }

                        $res = array_intersect(
                            $interfaces,
                            $refl->getInterfaceNames()
                        );

                        return !empty($res);
                    })
                ),
                ConditionBuilder::create()->exact(
                    ConditionBuilder::create()->classes(),
                    ConditionBuilder::create()->extending(ItemList::class, ItemSet::class, ItemHashmap::class),
                    ConditionBuilder::create()->custom(function (DiscoveredStructure $structure) {
                        $refl = (new \ReflectionClass($structure->getFcqn()));
                        if ($refl->isAbstract()) {
                            return false;
                        }

                        return true;
                    })
                ),
                ConditionBuilder::create()->enums()
            )
            ->get();
        $this->assertEqualsCanonicalizing(
            $classesFound,
            $classes,
            json_encode($classesFound, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)
        );
    }

    public static function packageProvider(): Generator
    {
        foreach (Finder::create()->in(__DIR__ . '/../..')->depth(0)->sortByName()->directories() as $foundPath) {
            if (in_array($foundPath->getBasename(), ['fixtures', 'integration-tests', 'faker'])) {
                continue;
            }
            yield $foundPath->getBasename() => [$foundPath->getBasename()];
        }
    }
}