<?php
namespace Apie\CommonValueObjects;

use Apie\CommonValueObjects\Bridge\Symfony\AllowedCssInSpanSanitizer;
use Apie\Core\Attributes\CmsSingleInput;
use Apie\Core\Attributes\FakeMethod;
use Apie\Core\Attributes\ProvideIndex;
use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Core\ValueObjects\Interfaces\LengthConstraintStringValueObjectInterface;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;
use Apie\Core\ValueObjects\IsStringValueObject;
use Apie\CountWords\WordCounter;
use Faker\Generator;
use ReflectionClass;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

#[FakeMethod('createRandom')]
#[ProvideIndex('provideIndexes')]
#[CmsSingleInput(['html'])]
final class SafeHtml implements StringValueObjectInterface, LengthConstraintStringValueObjectInterface
{
    use IsStringValueObject;

    private static HtmlSanitizer $htmlSanitizer;

    private static function getHtmlSanitizer(): HtmlSanitizer
    {
        if (!isset(self::$htmlSanitizer)) {
            $config = (new HtmlSanitizerConfig())
                ->allowSafeElements()
                ->allowElement('b')
                ->allowElement('i')
                ->dropAttribute('id', [])
                ->forceAttribute('a', 'rel', 'noopener noreferrer')
                ->forceAttribute('a', 'target', '_blank')
                ->allowLinkSchemes(['https', 'http', 'mailto'])
                ->allowRelativeLinks(false)
                ->withMaxInputLength(1024 * 1024 * 21) // character limit is 20mb, but could be truncated
                ->dropElement('html')
                ->dropElement('head')
                ->dropElement('script')
                ->dropElement('style')
                ->allowAttribute('style', 'span')
                ->withAttributeSanitizer(new AllowedCssInSpanSanitizer());
            self::$htmlSanitizer = new HtmlSanitizer(
                $config
            );
        }

        return self::$htmlSanitizer;
    }

    public static function createRandom(Generator $factory): self
    {
        $randomHtmls = ['div', 'h1', 'h2', 'h3', 'p'];
        $string = '<div>';
        $counter = $factory->numberBetween(1, 3);
        for ($i = 0; $i < $counter; $i++) {
            $tag = $factory->randomElement($randomHtmls);
            $string .= '<' . $tag . '>' . $factory->text(50) . '</' . $tag . '>';
        }
        $string .= '</div>';
        return new self($string);
    }

    /**
     * @return array<string, int>
     */
    public function provideIndexes(): array
    {
        return WordCounter::countFromString(strip_tags(str_replace('<', ' <', $this->internal)));
    }

    public static function validate(string $input): void
    {
        if (strlen($input) > (1024 * 1024 * 20)) {
            throw new InvalidStringForValueObjectException($input, new ReflectionClass(__CLASS__));
        }
    }

    protected function convert(string $input): string
    {
        return self::getHtmlSanitizer()->sanitizeFor('body', $input);
    }

    public static function minStringLength(): int
    {
        return 0;
    }

    public static function maxStringLength(): int
    {
        return 1024 * 1024 * 20;
    }
}
