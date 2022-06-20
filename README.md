# apie-lib-monorepo

| . | . | . | 
| --- | --- | --- | 
| [common-value-objects](https://github.com/apie-lib/common-value-objects) | [composite-value-objects](https://github.com/apie-lib/composite-value-objects) | [core](https://github.com/apie-lib/core) | 
| [date-value-objects](https://github.com/apie-lib/date-value-objects) | [dateformat-to-regex](https://github.com/apie-lib/dateformat-to-regex) | [faker](https://github.com/apie-lib/faker) | 
| [fixtures](https://github.com/apie-lib/fixtures) | [schema-generator](https://github.com/apie-lib/schema-generator) | . | 

Apie is a suite of composer packages to work with domain objects. It tries to aim to follow a Domain-objects-first approach and not a database first approach that you find in many PHP
frameworks nowadays.

Apie is very opionated what a domain object is but following this opinion leads to some easy
tooling in processing domain objects in a automated way.

Also since PHP 8.1 everything should be typehinted, which means a lot more can be automated.

Some tooling possible with APIE:

| . | . | 
| --- | --- |
| Faking domain objects | For tests or seeding databases faking objects with proper contents is never made easier. If the faker enters wrong data, then it means your domain object is wrong. |
| Restful API (WIP) | Create a full REST API by checking your Domain Objects. |
| Creating Entities (WIP) | Doctrine is awesome, but using Doctrine entities result in a few problems if you try to process them as domain objects. We try to make a package to automatically convert domain objects in a doctrine database or viceversa.
| Naked Objects (WIP) | Creates a full CRUD for your Domain Objects. And again: if the interface sucks, then probably your domain object is wrong. |

You should read the [introduction](/docs/introduction.md) how Apie wants you to write domain objects and how it will help you in getting a quick 