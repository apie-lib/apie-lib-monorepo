<img src="https://raw.githubusercontent.com/apie-lib/apie-lib-monorepo/main/docs/apie-logo.svg" width="100px" align="left" />
<h1>regex-value-objects</h1>






 [![Latest Stable Version](https://poser.pugx.org/apie/regex-value-objects/v)](https://packagist.org/packages/apie/regex-value-objects) [![Total Downloads](https://poser.pugx.org/apie/regex-value-objects/downloads)](https://packagist.org/packages/apie/regex-value-objects) [![Latest Unstable Version](https://poser.pugx.org/apie/regex-value-objects/v/unstable)](https://packagist.org/packages/apie/regex-value-objects) [![License](https://poser.pugx.org/apie/regex-value-objects/license)](https://packagist.org/packages/apie/regex-value-objects) [![PHP Composer](https://apie-lib.github.io/projectCoverage/coverage-regex-value-objects.svg)](https://apie-lib.github.io/projectCoverage/regex-value-objects/index.html)  

[![PHP Composer](https://github.com/apie-lib/regex-value-objects/actions/workflows/php.yml/badge.svg?event=push)](https://github.com/apie-lib/regex-value-objects/actions/workflows/php.yml)

This package is part of the [Apie](https://github.com/apie-lib) library.
The code is maintained in a monorepo, so PR's need to be sent to the [monorepo](https://github.com/apie-lib/apie-lib-monorepo/pulls)

## Documentation
Value objects wrapping a full PHP regular expression (with delimiters), used by Apie to
validate patterns used elsewhere (e.g. as a constraint on other regex-based value
objects). Uses `apie/regex-tools` and `apie/core`'s string value object traits.

### Standalone usage
```bash
composer require apie/regex-value-objects
```

`PhpRegularExpression` accepts any pattern `preg_match` can compile and rejects invalid
ones during construction:
```php
use Apie\RegexValueObjects\PhpRegularExpression;

$pattern = new PhpRegularExpression('/^[A-Z]+$/');
```

`PhpSafeRegularExpression` additionally rejects patterns containing lookaheads/
lookbehinds or nested repetitions (e.g. `(a+)+`), which can cause catastrophic
backtracking, so it is the safer choice when the pattern comes from user input. Both
classes are ordinary PHP value objects and work without a framework.
