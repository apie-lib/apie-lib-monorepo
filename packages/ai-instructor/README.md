<img src="https://raw.githubusercontent.com/apie-lib/apie-lib-monorepo/main/docs/apie-logo.svg" width="100px" align="left" />
<h1>ai-instructor</h1>






 [![Latest Stable Version](https://poser.pugx.org/apie/ai-instructor/v)](https://packagist.org/packages/apie/ai-instructor) [![Total Downloads](https://poser.pugx.org/apie/ai-instructor/downloads)](https://packagist.org/packages/apie/ai-instructor) [![Latest Unstable Version](https://poser.pugx.org/apie/ai-instructor/v/unstable)](https://packagist.org/packages/apie/ai-instructor) [![License](https://poser.pugx.org/apie/ai-instructor/license)](https://packagist.org/packages/apie/ai-instructor) [![PHP Composer](https://apie-lib.github.io/projectCoverage/coverage-ai-instructor.svg)](https://apie-lib.github.io/projectCoverage/ai-instructor/index.html)  

[![PHP Composer](https://github.com/apie-lib/ai-instructor/actions/workflows/php.yml/badge.svg?event=push)](https://github.com/apie-lib/ai-instructor/actions/workflows/php.yml)

This package is part of the [Apie](https://github.com/apie-lib) library.
The code is maintained in a monorepo, so PR's need to be sent to the [monorepo](https://github.com/apie-lib/apie-lib-monorepo/pulls)

## Documentation
`apie/ai-instructor` is a PHP take on Python's Instructor library: it asks an LLM (OpenAI or a local Ollama
instance) to fill in a plain PHP class from a natural-language prompt, using the OpenAPI schema generated from
the class as the structure the LLM must follow.

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

### Standalone usage
Install with:

```bash
composer require apie/ai-instructor
```

Use one of the static factory methods on `Apie\AiInstructor\AiInstructor` to build a client without any framework:

```php
use Apie\AiInstructor\AiInstructor;

// ollama (default http://localhost:11434)
$instructor = AiInstructor::createForOllama('http://localhost:11434');
// openAI
$instructor = AiInstructor::createForOpenAi('api-key');
// any OpenAI-compatible endpoint
$instructor = AiInstructor::createForCustomConfig('api-key', 'http://localhost:11434/');

$result = $instructor->instruct(
    MovieReview::class,
    'tinyllama',
    'You are an AI bot that writes a movie review following the given format.',
    'I think the Lord of the Rings movie has dated terribly'
);
// $result is a MovieReview instance
```

### Symfony integration
Through `apie/apie-bundle`, `Apie\AiInstructor\AiInstructor` and its `AiClient` are registered automatically and
the `apie:ai-playground` console command becomes available. Configure the endpoint and key in `apie.yaml`:

```yaml
apie:
  ai:
    base_url: http://localhost:11434
    api_key: '%env(AI_API_KEY)%'
```

### Laravel integration
`apie/laravel-apie` registers the generated `Apie\AiInstructor\AiInstructorServiceProvider`, wiring up the same
`AiInstructor`/`AiClient` services and the `apie:ai-playground` command. Configure the endpoint and key in
`config/apie.php`:

```php
return [
    'ai' => [
        'base_url' => env('AI_BASE_URL', 'http://localhost:11434'),
        'api_key' => env('AI_API_KEY'),
    ],
];
```
