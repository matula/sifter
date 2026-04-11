<?php

namespace Matula\Sifter\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Matula\Sifter\Sifter;

class Sifted implements ValidationRule
{
    public function __construct(
        private ?int $abortCode = null,
    ) {
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // We only validate strings. Other types pass automatically.
        if (!is_string($value)) {
            return;
        }

        $detector = app(Sifter::class);
        $isSpam = $detector->isSpam($value);

        if ($isSpam && $this->abortCode !== null) {
            abort($this->abortCode);
        }

        if ($isSpam) {
            $fail('The :attribute is not a valid value.');
        }
    }
}
