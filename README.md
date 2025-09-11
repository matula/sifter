# Sifter for Laravel

Detect spammy or bot-like input in Laravel forms using lightweight, configurable heuristics. Sifter ships with several checks (vowel ratio, repetitive chars, excessive capitals, numeric characters, entropy, and consecutive runs) and integrates with Laravel validation out of the box.

## About
I've been seeing accounts registered with the name field like "BBXQxwcf" or "DlaKsvRqiFA", and they even subvert Cloudflare and a honeypot. These are obvious spam accounts, so I decided to create a simple validation to help tackle most of these.  
Keep in mind, this is a brute-force validation and only targeting English characters and names. You can use the config file to get more lenient or strict about the rules, but the defaults are what I found to catch most of spam. 
Personally, I do a programmatic check just on the 'name' field, and redirect to a `404` if it fails... hoping that will dissuade more attempts. 

## Requirements
- PHP 8.2+
- Laravel 11.x or 12.x

## Installation
```bash
composer require matula/sifter
```
Sifter uses Laravel package auto-discovery; no manual provider registration is required.

## Publish Configuration (optional)
```bash
php artisan vendor:publish --provider="Matula\\Sifter\\SifterServiceProvider" --tag=config
```
This creates `config/sifter.php`. Toggle globally with `SIFTER_ENABLED` and tune each check under the `checks` key.

## Usage
### As a validation rule
Add the shortcut rule or the class-based rule.
```php
// app/Http/Controllers/ExampleController.php
$request->validate([
    'name' => ['required', 'string', 'sifted'],
    // or: new \\Matula\\Sifter\\Rules\\Sifted(),
]);
```
On failure, the rule reports that the value is not valid.

### Programmatic check
```php
use Matula\\Sifter\\Sifter;

$sifter = app(Sifter::class);

if ($sifter->isSpam($input)) {
    // handle spammy input
}

// Get more detail for logging/debugging
$results = $sifter->analyze($input); // [ [ 'name' => 'vowel_ratio', 'message' => '...', 'meta' => [...] ], ... ]
```

## Configuration Overview
`config/sifter.php` (excerpt):
```php
return [
  'enabled' => env('SIFTER_ENABLED', true),
  'checks' => [
    'excessive_capitals'      => ['enabled' => true, 'max_count' => 3],
    'vowel_ratio'             => ['enabled' => true, 'min_ratio' => 0.2],
    'consecutive_consonants'  => ['enabled' => true, 'max_consecutive' => 4],
    'consecutive_vowels'      => ['enabled' => true, 'max_consecutive' => 2],
    'repetitive_chars'        => ['enabled' => true, 'max_repetitive' => 2],
    'numeric_chars'           => ['enabled' => true],
    'high_entropy'            => ['enabled' => true, 'max_ratio' => 0.8],
  ],
];
```

## Custom Checks (extensible)
Implement the `CheckInterface` and tag it so Sifter picks it up.
```php
namespace App\\Sifter;

use Matula\\Sifter\\Checks\\CheckInterface;

class MyCheck implements CheckInterface {
    public function name(): string { return 'my_check'; }
    public function evaluate(string $input, array $config): ?array {
        return str_contains($input, 'zzz') ? [
            'message' => 'Contains forbidden sequence',
            'meta' => [],
        ] : null;
    }
}
```
Register and tag in a service provider:
```php
$this->app->singleton(MyCheck::class);
$this->app->tag([MyCheck::class], 'sifter.check');
```
Enable and configure under `config/sifter.php` → `checks['my_check']`.

## Local Development
- Install deps: `composer install`
- Run tests (Pest): `composer test`

## License
MIT
