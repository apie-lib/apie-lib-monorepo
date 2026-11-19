<?php
namespace Apie\Faker\FileFakers;

use Apie\Faker\Interfaces\ApieFileFaker;
use Apie\Faker\Tabular\RandomRecords;
use DateTimeImmutable;
use Faker\Generator;
use Nyholm\Psr7\Stream;

/**
 * Generates a landing page styled with the tailwindcss play cdn.
 * It always has a navbar, hero and footer; other sections are picked randomly in a random order.
 */
final class HtmlFileFaker implements ApieFileFaker
{
    private const COLORS = ['indigo', 'emerald', 'rose', 'sky', 'amber', 'violet', 'teal', 'orange', 'fuchsia'];
    private const ICONS = ['⚡', '🔒', '🚀', '🎯', '💡', '🧩', '📦', '🌍', '🔧', '📈', '🎨', '🛡️'];
    private const SECTIONS = ['features', 'pricing', 'table', 'testimonials', 'cta', 'faq'];

    public function createOriginalFilename(Generator $faker): string
    {
        return $faker->word() . '.html';
    }

    public function createMimeType(): string
    {
        return 'text/html';
    }

    public static function isSupported(): bool
    {
        return true;
    }

    /** @return resource */
    public function createResource(Generator $faker, string $originalFilename, string $mimeType): mixed
    {
        return Stream::create($this->createHtml($faker))->detach();
    }

    private function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5);
    }

    private function createHtml(Generator $faker): string
    {
        $color = $faker->randomElement(self::COLORS);
        $dark = $faker->boolean(30);
        $company = $faker->company();
        $body = $dark ? 'bg-slate-900 text-slate-100' : 'bg-white text-slate-800';
        $sections = $faker->randomElements(self::SECTIONS, $faker->numberBetween(2, 5));

        $html = '';
        foreach ($sections as $section) {
            $html .= $this->$section($faker, $color, $dark);
        }

        return '<!DOCTYPE html>' . "\n"
            . '<html lang="en">' . "\n"
            . '<head>' . "\n"
            . '  <meta charset="utf-8">' . "\n"
            . '  <meta name="viewport" content="width=device-width, initial-scale=1">' . "\n"
            . '  <title>' . $this->e($company . ' - ' . $faker->catchPhrase()) . '</title>' . "\n"
            . '  <script src="https://cdn.tailwindcss.com"></script>' . "\n"
            . '</head>' . "\n"
            . '<body class="' . $body . ' antialiased">' . "\n"
            . $this->navbar($faker, $company, $color, $dark)
            . '<main>' . "\n"
            . $this->hero($faker, $color, $dark)
            . $html
            . '</main>' . "\n"
            . $this->footer($faker, $company, $dark)
            . '</body>' . "\n"
            . '</html>' . "\n";
    }

    private function muted(bool $dark): string
    {
        return $dark ? 'text-slate-400' : 'text-slate-600';
    }

    private function card(bool $dark): string
    {
        return $dark ? 'bg-slate-800 border-slate-700' : 'bg-white border-slate-200';
    }

    private function navbar(Generator $faker, string $company, string $color, bool $dark): string
    {
        $links = '';
        foreach ($faker->words($faker->numberBetween(3, 5)) as $word) {
            $links .= '<a href="#" class="hover:text-' . $color . '-500">' . $this->e(ucfirst($word)) . '</a>';
        }
        $border = $dark ? 'border-slate-700' : 'border-slate-200';

        return '<header class="border-b ' . $border . '">'
            . '<nav class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">'
            . '<a href="#" class="text-xl font-bold text-' . $color . '-500">' . $this->e($company) . '</a>'
            . '<div class="hidden gap-6 text-sm font-medium md:flex">' . $links . '</div>'
            . '<a href="#" class="rounded-lg bg-' . $color . '-600 px-4 py-2 text-sm font-semibold text-white hover:bg-' . $color . '-500">'
            . $this->e(ucfirst($faker->word())) . '</a>'
            . '</nav></header>' . "\n";
    }

    private function hero(Generator $faker, string $color, bool $dark): string
    {
        $title = $this->e(rtrim($faker->sentence($faker->numberBetween(3, 7)), '.'));
        $text = $this->e($faker->paragraph());
        $button = '<a href="#" class="rounded-lg bg-' . $color . '-600 px-6 py-3 font-semibold text-white shadow hover:bg-' . $color . '-500">'
            . $this->e(ucfirst($faker->word())) . ' now</a>'
            . '<a href="#" class="rounded-lg border px-6 py-3 font-semibold ' . $this->card($dark) . '">Learn more</a>';
        $gradient = 'bg-gradient-to-br from-' . $color . '-400 to-' . $faker->randomElement(self::COLORS) . '-600';

        if ($faker->boolean()) {
            return '<section class="mx-auto max-w-4xl px-6 py-24 text-center">'
                . '<h1 class="text-4xl font-extrabold tracking-tight sm:text-6xl">' . $title . '</h1>'
                . '<p class="mt-6 text-lg ' . $this->muted($dark) . '">' . $text . '</p>'
                . '<div class="mt-10 flex justify-center gap-4">' . $button . '</div>'
                . '</section>' . "\n";
        }

        return '<section class="mx-auto grid max-w-6xl items-center gap-12 px-6 py-20 md:grid-cols-2">'
            . '<div><h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl">' . $title . '</h1>'
            . '<p class="mt-6 text-lg ' . $this->muted($dark) . '">' . $text . '</p>'
            . '<div class="mt-8 flex gap-4">' . $button . '</div></div>'
            . '<div class="flex h-72 items-center justify-center rounded-3xl text-7xl shadow-xl ' . $gradient . '">'
            . $faker->randomElement(self::ICONS) . '</div>'
            . '</section>' . "\n";
    }

    private function features(Generator $faker, string $color, bool $dark): string
    {
        $cards = '';
        foreach ($faker->randomElements(self::ICONS, $faker->randomElement([3, 3, 6])) as $icon) {
            $cards .= '<div class="rounded-2xl border p-6 shadow-sm ' . $this->card($dark) . '">'
                . '<div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-' . $color . '-100 text-2xl">' . $icon . '</div>'
                . '<h3 class="text-lg font-semibold">' . $this->e(ucfirst($faker->words(2, true))) . '</h3>'
                . '<p class="mt-2 text-sm ' . $this->muted($dark) . '">' . $this->e($faker->sentence(12)) . '</p>'
                . '</div>';
        }

        return '<section class="mx-auto max-w-6xl px-6 py-16">'
            . '<h2 class="mb-10 text-center text-3xl font-bold">' . $this->e(ucfirst($faker->words(3, true))) . '</h2>'
            . '<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">' . $cards . '</div></section>' . "\n";
    }

    private function pricing(Generator $faker, string $color, bool $dark): string
    {
        $cards = '';
        $highlight = $faker->numberBetween(0, 2);
        foreach (['Basic', 'Pro', 'Enterprise'] as $index => $plan) {
            $border = $index === $highlight ? 'border-2 border-' . $color . '-500 shadow-lg' : 'border';
            $items = '';
            for ($i = 0; $i < $faker->numberBetween(3, 5); $i++) {
                $items .= '<li class="flex gap-2"><span class="text-' . $color . '-500">✓</span>' . $this->e(ucfirst($faker->words(3, true))) . '</li>';
            }
            $cards .= '<div class="rounded-2xl p-8 ' . $border . ' ' . $this->card($dark) . '">'
                . '<h3 class="text-xl font-semibold">' . $plan . '</h3>'
                . '<p class="mt-4 text-4xl font-extrabold">$' . $faker->numberBetween(5, 99) * ($index + 1) . '<span class="text-base font-normal ' . $this->muted($dark) . '">/mo</span></p>'
                . '<ul class="mt-6 space-y-3 text-sm">' . $items . '</ul>'
                . '<a href="#" class="mt-8 block rounded-lg bg-' . $color . '-600 py-2 text-center font-semibold text-white hover:bg-' . $color . '-500">Choose</a>'
                . '</div>';
        }

        return '<section class="mx-auto max-w-6xl px-6 py-16"><h2 class="mb-10 text-center text-3xl font-bold">Pricing</h2>'
            . '<div class="grid gap-6 md:grid-cols-3">' . $cards . '</div></section>' . "\n";
    }

    private function table(Generator $faker, string $color, bool $dark): string
    {
        $records = RandomRecords::create($faker, 4, 12);
        $head = '';
        foreach ($records->getColumnNames() as $name) {
            $head .= '<th class="px-4 py-3 text-left font-semibold">' . $this->e($name) . '</th>';
        }
        $rows = '';
        foreach ($records as $index => $record) {
            $stripe = $index % 2 === 1 ? ($dark ? 'bg-slate-800/50' : 'bg-slate-50') : '';
            $rows .= '<tr class="' . $stripe . '">';
            foreach ($record as $value) {
                $rows .= '<td class="px-4 py-3">'
                    . $this->e($value instanceof DateTimeImmutable ? $value->format('Y-m-d') : (string) $value)
                    . '</td>';
            }
            $rows .= '</tr>';
        }

        return '<section class="mx-auto max-w-6xl px-6 py-16">'
            . '<h2 class="mb-6 text-2xl font-bold">' . $this->e(ucfirst($faker->words(2, true))) . '</h2>'
            . '<div class="overflow-x-auto rounded-xl border ' . $this->card($dark) . '">'
            . '<table class="min-w-full text-sm"><thead class="bg-' . $color . '-600 text-white"><tr>' . $head . '</tr></thead>'
            . '<tbody>' . $rows . '</tbody></table></div></section>' . "\n";
    }

    private function testimonials(Generator $faker, string $color, bool $dark): string
    {
        $cards = '';
        for ($i = 0; $i < 3; $i++) {
            $name = $faker->name();
            $cards .= '<figure class="rounded-2xl border p-6 ' . $this->card($dark) . '">'
                . '<blockquote class="italic ' . $this->muted($dark) . '">“' . $this->e($faker->sentence(14)) . '”</blockquote>'
                . '<figcaption class="mt-4 flex items-center gap-3">'
                . '<span class="flex h-10 w-10 items-center justify-center rounded-full bg-' . $color . '-500 font-bold text-white">' . $this->e(mb_substr($name, 0, 1)) . '</span>'
                . '<span><span class="block text-sm font-semibold">' . $this->e($name) . '</span>'
                . '<span class="block text-xs ' . $this->muted($dark) . '">' . $this->e($faker->jobTitle()) . '</span></span>'
                . '</figcaption></figure>';
        }

        return '<section class="mx-auto max-w-6xl px-6 py-16"><div class="grid gap-6 md:grid-cols-3">' . $cards . '</div></section>' . "\n";
    }

    private function cta(Generator $faker, string $color, bool $dark): string
    {
        return '<section class="px-6 py-16"><div class="mx-auto max-w-4xl rounded-3xl bg-' . $color . '-600 px-8 py-14 text-center text-white">'
            . '<h2 class="text-3xl font-bold">' . $this->e(rtrim($faker->sentence(5), '.')) . '</h2>'
            . '<p class="mt-4 opacity-90">' . $this->e($faker->sentence(12)) . '</p>'
            . '<a href="#" class="mt-8 inline-block rounded-lg bg-white px-6 py-3 font-semibold text-' . $color . '-700 hover:bg-slate-100">'
            . $this->e(ucfirst($faker->word())) . '</a></div></section>' . "\n";
    }

    private function faq(Generator $faker, string $color, bool $dark): string
    {
        $items = '';
        for ($i = 0; $i < $faker->numberBetween(3, 5); $i++) {
            $items .= '<details class="rounded-xl border p-4 ' . $this->card($dark) . '">'
                . '<summary class="cursor-pointer font-semibold">' . $this->e(ucfirst($faker->words(4, true))) . '?</summary>'
                . '<p class="mt-3 text-sm ' . $this->muted($dark) . '">' . $this->e($faker->paragraph()) . '</p></details>';
        }

        return '<section class="mx-auto max-w-3xl px-6 py-16"><h2 class="mb-8 text-center text-3xl font-bold">FAQ</h2>'
            . '<div class="space-y-3">' . $items . '</div></section>' . "\n";
    }

    private function footer(Generator $faker, string $company, bool $dark): string
    {
        $border = $dark ? 'border-slate-700' : 'border-slate-200';

        return '<footer class="border-t ' . $border . ' py-8 text-center text-sm ' . $this->muted($dark) . '">'
            . '&copy; ' . $faker->year() . ' ' . $this->e($company) . '. ' . $this->e($faker->catchPhrase()) . '.</footer>' . "\n";
    }
}
