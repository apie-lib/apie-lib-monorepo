<img src="https://raw.githubusercontent.com/apie-lib/apie-lib-monorepo/main/docs/apie-logo.svg" width="100px" align="left" />
<h1>text-value-objects</h1>






 [![Latest Stable Version](https://poser.pugx.org/apie/text-value-objects/v)](https://packagist.org/packages/apie/text-value-objects) [![Total Downloads](https://poser.pugx.org/apie/text-value-objects/downloads)](https://packagist.org/packages/apie/text-value-objects) [![Latest Unstable Version](https://poser.pugx.org/apie/text-value-objects/v/unstable)](https://packagist.org/packages/apie/text-value-objects) [![License](https://poser.pugx.org/apie/text-value-objects/license)](https://packagist.org/packages/apie/text-value-objects) [![PHP Composer](https://apie-lib.github.io/projectCoverage/coverage-text-value-objects.svg)](https://apie-lib.github.io/projectCoverage/text-value-objects/index.html)  

[![PHP Composer](https://github.com/apie-lib/text-value-objects/actions/workflows/php.yml/badge.svg?event=push)](https://github.com/apie-lib/text-value-objects/actions/workflows/php.yml)

This package is part of the [Apie](https://github.com/apie-lib) library.
The code is maintained in a monorepo, so PR's need to be sent to the [monorepo](https://github.com/apie-lib/apie-lib-monorepo/pulls)

## Documentation
This package is a collection of common text value objects that can be use in Apie, DTO's or Plain old PHP objects. Just add the package and include any of them. Why using value objects over strings? Please feel free to check out this blog article about the [value of value objects](https://apie-lib.blogspot.com/2023/10/the-value-of-value-objects.html).

### General usage
All of them have 2 methods: fromNative and toNative. With fromNative I can create an instance from a string or an integer if applicable. If it is not you will get an exception. Some also santize their input, like trimming spaces etc.

```php
use Apie\TextValueObjects\FirstName;

$valueObject = FirstName::fromNative('   George   ');
$valueObject->toNative(); // returns 'George'
FirstName::fromNative(' '); //throws error as this results in an empty first name
```
In combination with fakerphp you can also create random instances of a value object with sensible values. In almost all cases this is done with a createRandom method, but it could be possible you require [apie/faker](https://github.com/apie-lib/faker) to get more features.

### CompanyName
Represents a company name. A company name can contain quite a lot of different values and we try to avoid making to many assumptions. In general a company name can not be an empty string and does not contain spaces before or after the name.
```php
use Apie\TextValueObjects\CompanyName;
use Faker\Factory;

$valueObject = new CompanyName('Github');
$valueObject = CompanyName::fromNative('Github');
$valueObject->toNative();

// creating a random company name with faker:
$faker = Factory::create();
$companyName = CompanyName::createRandom($faker);

// these are considered invalid company names:
CompanyName::fromNative('');
Companyname::fromNative(' ');
CompanyName::fromNative('"');
```

### EncryptedPassword
Represents an encrypted password. Normally you only use this internally in a domain object and you do not expose it with a getter. In Apie an encrypted password field will never be indexed or searchable for security reasons. A password is encrypted in such a way that you can only verify if an entered password is correct.
```php
use Apie\TextValueObjects\EncryptedPassword;
use Apie\TextValueObjects\StrongPassword;
use Faker\Factory;

$valueObject = EncryptedPassword::fromUnencryptedPassword('test');
$valueObject = EncryptedPassword::fromNative('$2y$10$pBTplNYGIJNftnKerNAn/.citOq6BpKJ6f1fEtD1sfe3qD4ZCfJl.');
$valueObject = new EncryptedPassword('$2y$10$pBTplNYGIJNftnKerNAn/.citOq6BpKJ6f1fEtD1sfe3qD4ZCfJl.');
$valueObject->verifyUnencryptedPassword('test'); // returns true
$valueObject->verifyUnencryptedPassword('hi'); // return false

// this throws no error, but it will never verify an unencrypted password:
$valueObject = EncryptedPassword::fromNative('');

// it can be used in combination of StrongPassword value object:
$valueObject = EncryptedPassword::fromUnencryptedPassword(StrongPassword::fromNative('Test12!22T'));

// creating a random encrypted password with faker. Remember they are not crytographically secure passwords.
// This is only useful in seeding anyway as we never know the random password being used here.
$generator = Factory::create();
$companyName = CompanyName::createRandom($faker);
```

### FirstName
Represents a first name of a person. It will accept male and female names as it has no awareness if a name is male or female. In fact no assumptions can be made about names, except that they are not empty.

```php
use Apie\TextValueObjects\FirstName;
use Faker\Factory;

$valueObject = new FirstName('Pieter');
$valueObject = FirstName::fromNative('Pieter');
$valueObject->toNative();

// creating a random first name with faker:
$faker = Factory::create();
$companyName = FirstName::createRandom($faker);

// these are considered invalid first names:
FirstName::fromNative('');
Firstname::fromNative(' ');
FirstName::fromNative('"');
```

### LastName
Represents a last name of a person. No assumptions can be made about names, except that they are not empty.

```php
use Apie\TextValueObjects\LastName;
use Faker\Factory;

$valueObject = new LastName('Jordaan');
$valueObject = LastName::fromNative('Jordaan');
$valueObject->toNative();

// creating a random last name with faker:
$faker = Factory::create();
$companyName = LastName::createRandom($faker);

// these are considered invalid last names:
LastName::fromNative('');
Lastname::fromNative(' ');
LastName::fromNative('"');
```

You can combine first name and last name with apie/core composite value objects:

```php
use Apie\Core\ValueObjects\CompositeValueObject;
use Apie\Core\ValueObjects\Interfaces\ValueObjectInterface;
use Apie\TextValueObjects\FirstName;
use Apie\TextValueObjects\LastName;
use Stringable;

class FirstAndLastName implements ValueObjectInterface, Stringable
{
    use CompositeValueObject;
    public function __construct(
        private FirstName $firstName,
        private LastName $lastName
    ) {
    }

    public function __toString(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}

$valueObject = FirstAndLastName::fromNative(['firstName' => 'George', 'lastName' => 'Big']);
// this throws a validation error as expected
$valueObject = FirstAndLastName::fromNative(['firstName' => '', 'lastName' => 'Big']);
```
### NonEmptyString
This value object accepts any string, except empty strings. It does however trim the input from leading and trailing spaces.

### SmallDatabaseText
Often people typehint a property as string and forget adding validation if the string length is not too long. The result is a database error on storing the value as the value is too long. So the maximum string length is always business logic of a property. For that reason we have SmallDatabaseText that allows any string shorter than 255 characters (the maximum value of VARCHAR in most SQL vendors).

### StrongPassword
For applications you want people to have a secure user password and not a name like 'welcome' as password.
StrongPassword will only accept strings that have these properties:
- Minimum length 6
- Maximum length of 42 (longer passwords are not hashed better)
- At least one character of "!@#$%^&*()-_+."
- At least one digit
- At least one lowercase character
- At least one uppercase character

```php
use Apie\Core\Randomizer\RandomizerFromFaker;
use Apie\Core\Randomizer\SecureRandomizer;
use Apie\TextValueObjects\StrongPassword;
use Faker\Factory;

$valueObject = new StrongPassword('Test1!2');
$valueObject = StrongPassword::fromNative('Test1!2');
$valueObject->toNative();

// creating a random password that is cryptographically secure:
$valueObject = StrongPassword::createRandom(new SecureRandomizer());
// creating a less secure pasword with faker
$faker = Factory::create();
$valueObject = StrongPassword::createRandom(new RandomizerFromFaker($faker));

// give me a regular expressions with lookahead for matching StrongPassword:
StrongPassword::getRegularExpression();

// these are considered invalid strong passwords
StrongPassword::fromNative('');
StrongPassword::fromNative('welcome123');
StrongPassword::fromNative('HI');
```