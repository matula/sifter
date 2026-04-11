<?php

namespace Matula\Sifter;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Matula\Sifter\Checks\VowelRatioCheck;
use Matula\Sifter\Checks\RepetitiveCharsCheck;
use Matula\Sifter\Checks\ExcessiveCapitalsCheck;
use Matula\Sifter\Checks\ConsecutiveConsonantsCheck;
use Matula\Sifter\Checks\ConsecutiveVowelsCheck;
use Matula\Sifter\Checks\NumericCharsCheck;
use Matula\Sifter\Checks\HighEntropyCheck;

class SifterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/sifter.php' => config_path('sifter.php'),
            ], 'config');
        }

        // Register a convenient string-based alias for the validation rule
        Validator::extend('sifted', function ($attribute, $value, $parameters, $validator) {
            if (!is_string($value)) {
                return true;
            }

            $abortCode = null;

            if (!empty($parameters)) {
                $firstParameter = $parameters[0];

                if (is_numeric($firstParameter)) {
                    $abortCode = (int) $firstParameter;
                }
            }

            $detector = app(Sifter::class);
            $isSpam = $detector->isSpam($value);

            if ($isSpam && $abortCode !== null) {
                abort($abortCode);
            }

            return !$isSpam;
        }, 'The :attribute is not a valid value.');
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/sifter.php', 'sifter');

        // Register built-in checks and tag them for discovery
        $this->app->singleton(VowelRatioCheck::class, fn () => new VowelRatioCheck());
        $this->app->singleton(RepetitiveCharsCheck::class, fn () => new RepetitiveCharsCheck());
        $this->app->singleton(ExcessiveCapitalsCheck::class, fn () => new ExcessiveCapitalsCheck());
        $this->app->singleton(ConsecutiveConsonantsCheck::class, fn () => new ConsecutiveConsonantsCheck());
        $this->app->singleton(ConsecutiveVowelsCheck::class, fn () => new ConsecutiveVowelsCheck());
        $this->app->singleton(NumericCharsCheck::class, fn () => new NumericCharsCheck());
        $this->app->singleton(HighEntropyCheck::class, fn () => new HighEntropyCheck());
        $this->app->tag([
            VowelRatioCheck::class,
            RepetitiveCharsCheck::class,
            ExcessiveCapitalsCheck::class,
            ConsecutiveConsonantsCheck::class,
            ConsecutiveVowelsCheck::class,
            NumericCharsCheck::class,
            HighEntropyCheck::class,
        ], 'sifter.check');

        // Register the main class and inject tagged checks
        $this->app->singleton(Sifter::class, function ($app) {
            $checks = $app->tagged('sifter.check');
            return new Sifter(config('sifter'), $checks);
        });
    }
}
