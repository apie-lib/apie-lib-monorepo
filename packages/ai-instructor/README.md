<img src="https://raw.githubusercontent.com/apie-lib/apie-lib-monorepo/main/docs/apie-logo.svg" width="100px" align="left" />
<h1>ai-instructor</h1>






 [![Latest Stable Version](https://poser.pugx.org/apie/ai-instructor/v)](https://packagist.org/packages/apie/ai-instructor) [![Total Downloads](https://poser.pugx.org/apie/ai-instructor/downloads)](https://packagist.org/packages/apie/ai-instructor) [![Latest Unstable Version](https://poser.pugx.org/apie/ai-instructor/v/unstable)](https://packagist.org/packages/apie/ai-instructor) [![License](https://poser.pugx.org/apie/ai-instructor/license)](https://packagist.org/packages/apie/ai-instructor) [![PHP Composer](https://apie-lib.github.io/projectCoverage/coverage-ai-instructor.svg)](https://apie-lib.github.io/projectCoverage/ai-instructor/index.html)  

[![PHP Composer](https://github.com/apie-lib/ai-instructor/actions/workflows/php.yml/badge.svg?event=push)](https://github.com/apie-lib/ai-instructor/actions/workflows/php.yml)

This package is part of the [Apie](https://github.com/apie-lib) library.
The code is maintained in a monorepo, so PR's need to be sent to the [monorepo](https://github.com/apie-lib/apie-lib-monorepo/pulls)

## Documentation
Instructor is a library for Python that works with LLM's to force a specific structure. Wouldn't it be nice if we have the same functionality in PHP? That's what apie/ai-instructor does. Like you have some class in PHP and ask AI to fill it in for you from a chat prompt given by the user:

```php
class MovieReview {
    public function __construct(
        public string $name,
        public string $description,
        public int $rating
    ) {
    }
}
```

### Requirements
You need a OpenAI key or a valid ollama service running (in Docker or locally).

### Setup
The simplest standalone setup is using any of the static methods in ```AiInstructor```:

```php
// ollama
$instructor = AiInstructor::createForOllama('http://localhost:11434');
// openAI
$instructor = AiInstructor::createForOpenAi('api-key');
// custom:
$instructor = AiInstructor::createForCustomConfig(
    'api-key',
    'http://localhost:11434/'
);
$result = $instructor->instruct(
    MovieReview::class,
    'tinyllama',
    'You are an AI bot that comes up with a movie review for a movie made from the description given by the user. It should follow the format given. If you can not come up with a movie review of the description given by the user, then make a review of a random Hollywood movie.',
    'I think the Lord of the Rings movie has dated terrible'
);
dump($result); // dumps a MovieReview instance.
```

You can also set it up with the [Apie library](https://github.com/apie-lib/apie-lib-monorepo). In This case you would need to require apie/apie-bundle for Symfony or apie/laravel-apie for Laravel to setup the key and url in the Laravel/Symfony configuration:
```yaml
apie:
  ai:
    base_url: http://localhost:11434
    api_key: 'ignored-for-ollama'
```

It is recommended to use environment variables for the api key:
Symfony:
```yaml
apie:
  ai:
    api_key: '%env(AI_API_KEY)%'
```
Laravel:
```php
// config/apie.php
return [
    'ai' => [
        'api_key' => env('AI_API_KEY'),
    ]
];
```
