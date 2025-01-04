<?php

namespace Apie\Fixtures\Php84;

if (PHP_VERSION_ID >= 80400) {
    eval('
    class AsyncVisibility implements DtoInterface
    {
        public function __construct(
            public readonly string $name,
            public private(set) string $option
        ) {
        }
    }');
}
