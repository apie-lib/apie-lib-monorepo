<?php
namespace Apie\HtmlBuilders\Factories;

use Apie\Core\Context\ApieContext;
use Apie\HtmlBuilders\Columns\ColumnSelector;
use Apie\HtmlBuilders\FieldDisplayBuildContext;
use Apie\HtmlBuilders\FieldDisplayProviders\ArrayDisplayProvider;
use Apie\HtmlBuilders\FieldDisplayProviders\BooleanDisplayProvider;
use Apie\HtmlBuilders\FieldDisplayProviders\EnumDisplayProvider;
use Apie\HtmlBuilders\FieldDisplayProviders\FallbackDisplayProvider;
use Apie\HtmlBuilders\FieldDisplayProviders\ListDisplayProvider;
use Apie\HtmlBuilders\FieldDisplayProviders\NullDisplayProvider;
use Apie\HtmlBuilders\FieldDisplayProviders\SafeHtmlDisplayProvider;
use Apie\HtmlBuilders\FieldDisplayProviders\SegmentDisplayProvider;
use Apie\HtmlBuilders\FieldDisplayProviders\UploadedFileDisplayProvider;
use Apie\HtmlBuilders\FieldDisplayProviders\ValueObjectDisplayProvider;
use Apie\HtmlBuilders\Interfaces\ComponentInterface;
use Apie\HtmlBuilders\Interfaces\FieldDisplayComponentProviderInterface;

final class FieldDisplayComponentFactory
{
    /**
     * @var array<int, FieldDisplayComponentProviderInterface>
     */
    private array $valueComponentProviders;

    public function __construct(FieldDisplayComponentProviderInterface ...$valueComponentProviders)
    {
        $this->valueComponentProviders = $valueComponentProviders;
    }

    /**
     * @param iterable<int, FieldDisplayComponentProviderInterface> $customDisplayProviders
     */
    public static function create(
        iterable $customDisplayProviders,
        ?ColumnSelector $columnSelector = null
    ): self {
        return new self(
            ...[
                ...$customDisplayProviders,
                new SafeHtmlDisplayProvider(),
                new UploadedFileDisplayProvider(),
                new ArrayDisplayProvider(),
                new ListDisplayProvider($columnSelector ?? new ColumnSelector()),
                new ValueObjectDisplayProvider(),
                new EnumDisplayProvider(),
                new BooleanDisplayProvider(),
                new NullDisplayProvider(),
                new SegmentDisplayProvider(),
                new FallbackDisplayProvider(),
            ]
        );
    }

    private function doCreateDisplayFor(mixed $object, FieldDisplayBuildContext $fieldDisplayBuildContext): ComponentInterface
    {
        foreach ($this->valueComponentProviders as $valueComponentProvider) {
            if ($valueComponentProvider->supports($object, $fieldDisplayBuildContext)) {
                return $valueComponentProvider->createComponentFor($object, $fieldDisplayBuildContext);
            }
        }
        return (new FallbackDisplayProvider)->createComponentFor($object, $fieldDisplayBuildContext);
    }

    public function createDisplayFor(mixed $object, ApieContext $apieContext): ComponentInterface
    {
        $context = new FieldDisplayBuildContext(
            function (mixed $object, FieldDisplayBuildContext $fieldDisplayBuildContext) {
                return $this->doCreateDisplayFor($object, $fieldDisplayBuildContext);
            },
            $apieContext,
            $object
        );
        
        return $this->doCreateDisplayFor($object, $context);
    }
}
