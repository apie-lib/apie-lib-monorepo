# dateformat-to-regex

 [![Latest Stable Version](http://poser.pugx.org/apie/dateformat-to-regex/v)](https://packagist.org/packages/apie/dateformat-to-regex) [![Total Downloads](http://poser.pugx.org/apie/dateformat-to-regex/downloads)](https://packagist.org/packages/apie/dateformat-to-regex) [![Latest Unstable Version](http://poser.pugx.org/apie/dateformat-to-regex/v/unstable)](https://packagist.org/packages/apie/dateformat-to-regex) [![License](http://poser.pugx.org/apie/dateformat-to-regex/license)](https://packagist.org/packages/apie/dateformat-to-regex) [![PHP Version Require](http://poser.pugx.org/apie/dateformat-to-regex/require/php)](https://packagist.org/packages/apie/dateformat-to-regex) [![Code coverage](https://raw.githubusercontent.com/apie-lib/{package}/main/coverage_badge.svg)] 

[![PHP Composer](https://github.com/apie-lib/dateformat-to-regex/actions/workflows/php.yml/badge.svg?event=push)](https://github.com/apie-lib/dateformat-to-regex/actions/workflows/php.yml)

This package is part of the [Apie](https://github.com/apie-lib) library.
The code is maintained in a monorepo, so PR's need to be sent to the [monorepo](https://github.com/apie-lib/apie-lib-monorepo/pulls)

## Documentation
This package converts PHP date format strings into regular expressions. Right now it will create
the 'simple' regular expression. So it will validate 30 february as a valid date.

### Usage
```php
<?php

use Apie\DateformatToRegex\DateFormatToRegex;

$dateFormat = DateTime::ATOM;
$regularExpression = DateFormatToRegex::formatToRegex($dateFormat);
$dateString = '2020-01-28T16:22:37-07:00';

// echos 1 as the regular expression matches the date string.
var_dump(preg_match($regularExpression, $dateString));

```
