<?php



if (PHP_VERSION_ID >= 80400) {
    eval('
    namespace Apie\Fixtures\Php84;
    use Apie\Core\Dto\DtoInterface;
    class AsyncVisibility implements DtoInterface
    {
        public function __construct(
            public readonly string $name,
            public private(set) string $option
        ) {
        }
    }');
}
