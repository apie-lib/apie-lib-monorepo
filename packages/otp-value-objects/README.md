<img src="https://raw.githubusercontent.com/apie-lib/apie-lib-monorepo/main/docs/apie-logo.svg" width="100px" align="left" />
<h1>otp-value-objects</h1>






 [![Latest Stable Version](https://poser.pugx.org/apie/otp-value-objects/v)](https://packagist.org/packages/apie/otp-value-objects) [![Total Downloads](https://poser.pugx.org/apie/otp-value-objects/downloads)](https://packagist.org/packages/apie/otp-value-objects) [![Latest Unstable Version](https://poser.pugx.org/apie/otp-value-objects/v/unstable)](https://packagist.org/packages/apie/otp-value-objects) [![License](https://poser.pugx.org/apie/otp-value-objects/license)](https://packagist.org/packages/apie/otp-value-objects) [![PHP Composer](https://apie-lib.github.io/projectCoverage/coverage-otp-value-objects.svg)](https://apie-lib.github.io/projectCoverage/otp-value-objects/index.html)  

[![PHP Composer](https://github.com/apie-lib/otp-value-objects/actions/workflows/php.yml/badge.svg?event=push)](https://github.com/apie-lib/otp-value-objects/actions/workflows/php.yml)

This package is part of the [Apie](https://github.com/apie-lib) library.
The code is maintained in a monorepo, so PR's need to be sent to the [monorepo](https://github.com/apie-lib/apie-lib-monorepo/pulls)

## Documentation
Value objects for HOTP and TOTP one-time passwords, wrapping `spomky-labs/otphp` for
the OTP algorithms and `chillerlan/php-qrcode` for enrollment QR codes.

### Standalone usage
```bash
composer require apie/otp-value-objects
```

`OTP` validates a submitted one-time password string:
```php
use Apie\OtpValueObjects\OTP;

$otp = new OTP('123456');
```

`TOTPSecret::createRandom()` generates a new TOTP secret and `createOTP()` computes the
current code for it; `HOTPSecret::createRandom()` does the same for counter-based OTP.
Extend the abstract `VerifyOTP` class to add a two-factor-authentication action that
references an entity's OTP secret property and label. These are ordinary PHP value
objects and work without a framework, though the QR code helpers on `TOTPSecret` /
`HOTPSecret` are handy when building an enrollment screen in any Apie CMS/REST app.
